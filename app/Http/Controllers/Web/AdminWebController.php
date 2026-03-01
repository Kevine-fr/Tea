<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Participation;
use App\Models\Prize;
use App\Models\Redemption;
use App\Models\User;
use App\Services\ParticipationService;
use App\Services\RedemptionService;
use App\Services\TicketCodeService;
use Illuminate\Http\Request;

class AdminWebController extends Controller
{
    public function __construct(
        protected ParticipationService $participationService,
        protected RedemptionService $redemptionService,
        protected TicketCodeService $ticketCodeService,
    ) {}

    /**
     * GET /admin
     */
    public function dashboard()
    {
        $stats = $this->participationService->getStats();
        $recentParticipations = Participation::with(['user', 'ticketCode', 'prize'])
                                             ->latest('participation_date')
                                             ->limit(5)
                                             ->get();

        $pendingCount     = Redemption::where('status', 'pending')->count();
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

        if ($request->search) {
            $query->whereHas('user', fn($q) => $q->where('email', 'like', "%{$request->search}%"))
                  ->orWhereHas('ticketCode', fn($q) => $q->where('code', 'like', "%{$request->search}%"));
        }

        $participations = $query->paginate(20)->withQueryString();

        return view('admin.participations', compact('participations'));
    }

    /**
     * GET /admin/redemptions
     */
    public function redemptions()
    {
        $pending   = $this->redemptionService->getPending();
        $pendingCount = Redemption::where('status', 'pending')->count();

        return view('admin.redemptions', compact('pending', 'pendingCount'));
    }

    /**
     * PATCH /admin/redemptions/{id}/status
     */
    public function updateRedemptionStatus(Request $request, string $id)
    {
        $request->validate(['status' => ['required', 'in:approved,rejected,completed']]);

        $this->redemptionService->updateStatus($id, $request->status);

        return redirect()->back()->with('success', 'Statut mis à jour avec succès.');
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
    public function storePrize(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'stock'       => ['required', 'integer', 'min:0'],
        ]);

        Prize::create(['id' => \Illuminate\Support\Str::uuid(), ...$validated]);

        return redirect()->route('admin.prizes')->with('success', 'Lot créé avec succès !');
    }

    /**
     * PATCH /admin/prizes/{id}
     */
    public function updatePrize(Request $request, string $id)
    {
        $prize     = Prize::findOrFail($id);
        $validated = $request->validate([
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'stock'       => ['sometimes', 'integer', 'min:0'],
        ]);

        $prize->update($validated);
        return redirect()->route('admin.prizes')->with('success', 'Lot mis à jour.');
    }

    /**
     * GET /admin/tickets
     */
    public function tickets()
    {
        $stats = $this->ticketCodeService->getStats();
        return view('admin.tickets', compact('stats'));
    }

    /**
     * POST /admin/tickets/generate
     */
    public function generateTickets(Request $request)
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:10000']]);

        $created = $this->ticketCodeService->generateBatch($request->quantity);

        return redirect()->route('admin.tickets')
                         ->with('success', "{$created} codes tickets générés avec succès.");
    }

    /**
     * GET /admin/users
     */
    public function users(Request $request)
    {
        $users = User::with('role')
                     ->when($request->search, fn($q) => $q->where('email', 'like', "%{$request->search}%"))
                     ->orderBy('created_at', 'desc')
                     ->paginate(20)
                     ->withQueryString();

        return view('admin.users', compact('users'));
    }
}