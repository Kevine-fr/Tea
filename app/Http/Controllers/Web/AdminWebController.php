<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Participation;
use App\Models\Prize;
use App\Models\Redemption;
use App\Models\TicketCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AdminWebController extends Controller
{
    // ─── Dashboard ────────────────────────────────────────────────────────────
    public function dashboard()
    {
        // Statistiques
        $stats = [
            'total_tickets'        => TicketCode::count(),
            'total_participations' => Participation::count(),
            'total_winners'        => Participation::whereNotNull('prize_id')->count(),
            'pending_redemptions'  => Redemption::where('status', 'pending')->count(),
        ];

        // Répartition des gains (donut chart)
        $prizeDistribution = Participation::join('prizes', 'participations.prize_id', '=', 'prizes.id')
            ->select('prizes.name', DB::raw('COUNT(*) as count'))
            ->groupBy('prizes.id', 'prizes.name')
            ->get()
            ->map(fn($r) => ['name' => $r->name, 'count' => $r->count])
            ->toArray();

        // Tickets utilisés par jour (7 derniers jours, bar chart)
        $dailyTickets = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);
            return [
                'date'  => $date->format('d/m'),
                'count' => TicketCode::whereDate('updated_at', $date)->where('is_used', true)->count(),
            ];
        })->toArray();

        // Alertes stock faible
        $lowStockPrizes = Prize::where('stock', '<', 50)->get();

        return view('admin.dashboard', compact(
            'stats', 'prizeDistribution', 'dailyTickets', 'lowStockPrizes'
        ));
    }

    // ─── Participations ───────────────────────────────────────────────────────
    public function participations(Request $request)
    {
        $query = Participation::with(['user', 'ticketCode', 'prize', 'redemption'])
            ->latest('participation_date');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('ticketCode', fn($sq) => $sq->where('code', 'like', "%$q%"))
                  ->orWhereHas('user', fn($sq) => $sq->where('email', 'like', "%$q%")
                      ->orWhere('last_name', 'like', "%$q%")
                      ->orWhere('first_name', 'like', "%$q%"));
        }

        $participations = $query->paginate(20);

        return view('admin.participations', compact('participations'));
    }

    // ─── Tickets & Gains (vue combinée) ───────────────────────────────────────
    public function ticketsGains(Request $request)
    {
        $query = Participation::with(['user', 'ticketCode', 'prize', 'redemption'])
            ->whereNotNull('prize_id')
            ->latest('participation_date');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('ticketCode', fn($sq) => $sq->where('code', 'like', "%$q%"))
                  ->orWhereHas('user', fn($sq) => $sq->where('last_name', 'like', "%$q%")
                      ->orWhere('first_name', 'like', "%$q%")
                      ->orWhere('email', 'like', "%$q%"));
        }

        $ticketsGains = $query->paginate(20);

        return view('admin.tickets-gains', compact('ticketsGains'));
    }

    // ─── Mettre à jour le statut d'un gain (modal MAJ) ────────────────────────
    public function updateTicketGainStatus(Request $request, string $id)
    {
        $request->validate([
            'status'   => ['required', 'in:pending,approved,completed,rejected,store'],
            'deadline' => ['nullable', 'date'],
        ]);

        $participation = Participation::with('redemption')
            ->whereNotNull('prize_id')
            ->findOrFail($id);

        if ($participation->redemption) {
            $participation->redemption->update([
                'status'   => $request->status,
                'deadline' => $request->deadline,
            ]);
        } else {
            Redemption::create([
                'participation_id' => $participation->id,
                'status'           => $request->status,
                'method'           => 'store',
                'deadline'         => $request->deadline,
            ]);
        }

        return back()->with('success', 'Statut mis à jour avec succès.');
    }

    // ─── Remises ──────────────────────────────────────────────────────────────
    public function redemptions()
    {
        $redemptions = Redemption::with(['participation.user', 'participation.prize', 'participation.ticketCode'])
            ->latest()
            ->paginate(20);

        return view('admin.redemptions', compact('redemptions'));
    }

    public function updateRedemptionStatus(Request $request, string $id)
    {
        $request->validate(['status' => ['required', 'in:pending,approved,completed,rejected']]);

        Redemption::findOrFail($id)->update(['status' => $request->status]);

        return back()->with('success', 'Statut mis à jour.');
    }

    // ─── Lots ────────────────────────────────────────────────────────────────
    public function prizes()
    {
        $prizes = Prize::withCount('participations')->get();
        return view('admin.prizes', compact('prizes'));
    }

    public function storePrize(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'stock'       => ['required', 'integer', 'min:0'],
        ]);

        Prize::create($request->only('name', 'description', 'stock'));

        return back()->with('success', 'Lot créé avec succès.');
    }

    public function updatePrize(Request $request, string $id)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'stock'       => ['required', 'integer', 'min:0'],
        ]);

        Prize::findOrFail($id)->update($request->only('name', 'description', 'stock'));

        return back()->with('success', 'Lot mis à jour.');
    }

    // ─── Tickets ─────────────────────────────────────────────────────────────
    public function tickets()
    {
        $tickets = TicketCode::with('participation.user')->latest()->paginate(30);
        return view('admin.tickets', compact('tickets'));
    }

    public function generateTickets(Request $request)
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:10000']]);

        $quantity = $request->quantity;
        $inserted = 0;
        $batchSize = 500;

        while ($inserted < $quantity) {
            $toGenerate = min($batchSize, $quantity - $inserted);
            $codes = [];

            for ($i = 0; $i < $toGenerate * 3; $i++) {
                $codes[] = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 10));
            }

            $codes = array_unique($codes);
            $existing = TicketCode::whereIn('code', $codes)->pluck('code')->toArray();
            $newCodes = array_diff($codes, $existing);

            if (empty($newCodes)) break;

            $batch = array_slice(array_values($newCodes), 0, $toGenerate);
            $rows = array_map(fn($c) => ['code' => $c, 'is_used' => false, 'created_at' => now(), 'updated_at' => now()], $batch);

            TicketCode::insert($rows);
            $inserted += count($batch);
        }

        return back()->with('success', "$inserted tickets générés avec succès.");
    }

    // ─── Utilisateurs ─────────────────────────────────────────────────────────
    public function users(Request $request)
    {
        $query = User::withCount('participations')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($sq) => $sq->where('email', 'like', "%$q%")
                ->orWhere('last_name', 'like', "%$q%")
                ->orWhere('first_name', 'like', "%$q%"));
        }

        $users = $query->paginate(20);
        return view('admin.users', compact('users'));
    }

    // ─── Export CSV ───────────────────────────────────────────────────────────
    public function exportCsv()
    {
        $participations = Participation::with(['user', 'ticketCode', 'prize', 'redemption'])
            ->whereNotNull('prize_id')
            ->get();

        $filename = 'thetiptop_gains_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($participations) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM pour Excel
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, ['N° Ticket', 'Email', 'Nom', 'Prénom', 'Lot Gagné', 'Date Participation', 'Statut Réclamation', 'Méthode'], ';');

            foreach ($participations as $p) {
                fputcsv($out, [
                    $p->ticketCode?->code ?? '',
                    $p->user?->email ?? '',
                    $p->user?->last_name ?? '',
                    $p->user?->first_name ?? '',
                    $p->prize?->name ?? '',
                    $p->participation_date ? \Carbon\Carbon::parse($p->participation_date)->format('d/m/Y H:i') : '',
                    $p->redemption?->status ?? 'Aucune réclamation',
                    $p->redemption?->method ?? '',
                ], ';');
            }

            fclose($out);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}