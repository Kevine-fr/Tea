<?php
// ═══════════════════════════════════════════════════
// DashboardController.php
// ═══════════════════════════════════════════════════
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ParticipationService;
use App\Services\RedemptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected ParticipationService $participationService,
        protected RedemptionService $redemptionService,
    ) {}

    /**
     * GET /dashboard
     */
    public function index()
    {
        $user           = Auth::user();
        $participations = $this->participationService->getUserParticipations($user);
        $lastParticipation = $participations->first();

        return view('dashboard.index', compact('participations', 'lastParticipation'));
    }

    /**
     * POST /participate
     */
    public function participate(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'min:4', 'max:10'],
        ]);

        try {
            $participation = $this->participationService->participate(Auth::user(), $request->code);

            if ($participation->hasWon()) {
                $prize   = $participation->prize->name;
                $message = "🎉 Félicitations ! Vous avez remporté : {$prize}";
                return redirect()->route('dashboard')
                                 ->with('participation_success', $message)
                                 ->with('code_status', $message)
                                 ->with('code_status_type', 'success');
            } else {
                return redirect()->route('dashboard')
                                 ->with('participation_success', 'Merci pour votre participation ! Pas de lot cette fois-ci.')
                                 ->with('code_status', 'Participation enregistrée. Pas de lot cette fois-ci.')
                                 ->with('code_status_type', 'empty');
            }
        } catch (\App\Exceptions\AppException $e) {
            return redirect()->route('dashboard')
                             ->withErrors(['code' => $e->getMessage()])
                             ->with('code_status', $e->getMessage())
                             ->with('code_status_type', 'error');
        }
    }

    /**
     * GET /redemption/{participation}/create
     */
    public function createRedemption(string $participationId)
    {
        $user          = Auth::user();
        $participation = \App\Models\Participation::where('id', $participationId)
                                                  ->where('user_id', $user->id)
                                                  ->with('prize')
                                                  ->firstOrFail();

        if (!$participation->hasWon() || $participation->isRedeemed()) {
            return redirect()->route('dashboard');
        }

        return view('dashboard.redemption', compact('participation'));
    }

    /**
     * POST /redemption
     */
    public function storeRedemption(Request $request)
    {
        $validated = $request->validate([
            'participation_id' => ['required', 'uuid'],
            'method'           => ['required', 'in:store,mail,online'],
        ]);

        try {
            $this->redemptionService->request(
                Auth::user(),
                $validated['participation_id'],
                $validated['method']
            );

            return redirect()->route('dashboard')
                             ->with('success', '✅ Votre demande de réclamation a été soumise avec succès !');
        } catch (\App\Exceptions\AppException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}