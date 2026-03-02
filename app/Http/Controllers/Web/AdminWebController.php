<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Participation;
use App\Models\Prize;
use App\Models\Redemption;
use App\Models\TicketCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminWebController extends Controller
{
    /**
     * GET /admin — Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_participations'   => Participation::count(),
            'total_winners'          => Participation::whereNotNull('prize_id')->count(),
            'prizes_remaining_stock' => Prize::sum('stock'),
            'total_redemptions'      => Redemption::count(),
        ];

        $recentParticipations = Participation::with(['user', 'ticketCode', 'prize'])
            ->latest('participation_date')
            ->limit(5)
            ->get();

        $pendingCount = Redemption::where('status', 'pending')->count();

        $recentRedemptions = Redemption::where('status', 'pending')
            ->with(['participation.user', 'participation.prize'])
            ->orderBy('requested_at')
            ->limit(10)
            ->get();

        $prizes = Prize::orderBy('stock', 'desc')->get();

        return view('admin.dashboard', compact(
            'stats', 'recentParticipations',
            'pendingCount', 'recentRedemptions', 'prizes'
        ));
    }

    /**
     * GET /admin/participations
     */
    public function participations(Request $request)
    {
        $query = Participation::with(['user', 'ticketCode', 'prize', 'redemption'])
            ->orderBy('participation_date', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('email', 'like', "%{$search}%"))
                  ->orWhereHas('ticketCode', fn($t) => $t->where('code', 'like', "%{$search}%"));
            });
        }

        $participations = $query->paginate(20)->withQueryString();

        return view('admin.participations', compact('participations'));
    }

    /**
     * GET /admin/redemptions
     */
    public function redemptions()
    {
        $pending = Redemption::where('status', 'pending')
            ->with(['participation.user', 'participation.prize'])
            ->orderBy('requested_at')
            ->paginate(20);

        $pendingCount = Redemption::where('status', 'pending')->count();

        return view('admin.redemptions', compact('pending', 'pendingCount'));
    }

    /**
     * PATCH /admin/redemptions/{id}/status
     */
    public function updateRedemptionStatus(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:approved,rejected,completed'],
        ]);

        $redemption = Redemption::findOrFail($id);
        $redemption->update([
            'status'       => $request->status,
            'completed_at' => in_array($request->status, ['completed', 'rejected']) ? now() : null,
        ]);

        $labels = ['approved' => 'approuvée', 'rejected' => 'refusée', 'completed' => 'complétée'];
        return redirect()->back()
            ->with('success', "Réclamation {$labels[$request->status]} avec succès.");
    }

    /**
     * GET /admin/prizes
     */
    public function prizes()
    {
        $prizes = Prize::withCount('participations')->get();
        return view('admin.prizes', compact('prizes'));
    }

    /**
     * POST /admin/prizes
     */
    public function storePrize(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'stock'       => ['required', 'integer', 'min:0'],
        ]);

        Prize::create(['id' => Str::uuid(), ...$data]);

        return redirect()->route('admin.prizes')
            ->with('success', 'Lot créé avec succès !');
    }

    /**
     * PATCH /admin/prizes/{id}
     */
    public function updatePrize(Request $request, string $id): RedirectResponse
    {
        $prize = Prize::findOrFail($id);
        $data  = $request->validate([
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'stock'       => ['sometimes', 'integer', 'min:0'],
        ]);

        $prize->update($data);

        return redirect()->route('admin.prizes')
            ->with('success', 'Lot mis à jour.');
    }

    /**
     * GET /admin/tickets
     */
    public function tickets()
    {
        $stats = [
            'total'     => TicketCode::count(),
            'used'      => TicketCode::where('is_used', true)->count(),
            'available' => TicketCode::where('is_used', false)->count(),
        ];

        $recentTickets = TicketCode::orderBy('created_at', 'desc')->limit(20)->get();

        return view('admin.tickets', compact('stats', 'recentTickets'));
    }

    /**
     * POST /admin/tickets/generate
     */
    public function generateTickets(Request $request): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10000'],
        ]);

        $quantity  = $request->quantity;
        $generated = [];
        $inserted  = 0;

        while ($inserted < $quantity) {
            $batch = [];
            $needed = min(500, $quantity - $inserted);

            while (count($batch) < $needed) {
                $code = strtoupper(Str::random(10));
                if (!in_array($code, $generated) && !TicketCode::where('code', $code)->exists()) {
                    $generated[] = $code;
                    $batch[] = [
                        'id'         => Str::uuid(),
                        'code'       => $code,
                        'is_used'    => false,
                        'created_at' => now(),
                    ];
                }
            }

            TicketCode::insert($batch);
            $inserted += count($batch);
        }

        return redirect()->route('admin.tickets')
            ->with('success', "{$inserted} codes tickets générés avec succès !");
    }

    /**
     * GET /admin/users
     */
    public function users(Request $request)
    {
        $users = User::with('role')
            ->when($request->filled('search'), fn($q) =>
                $q->where('email', 'like', "%{$request->search}%")
            )
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users', compact('users'));
    }
}