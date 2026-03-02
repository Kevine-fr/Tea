<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Participation;
use App\Models\Prize;
use App\Models\TicketCode;
use App\Models\Redemption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * GET /dashboard
     */
    public function index()
    {
        $user = Auth::user();

        $participations = Participation::with(['ticketCode', 'prize', 'redemption'])
            ->where('user_id', $user->id)
            ->orderBy('participation_date', 'desc')
            ->paginate(10);

        $lastParticipation = $participations->first();

        return view('dashboard.index', compact('participations', 'lastParticipation'));
    }

    /**
     * POST /participate — Soumettre un code ticket
     */
    public function participate(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'min:4', 'max:20'],
        ], [
            'code.required' => 'Veuillez saisir votre code ticket.',
            'code.min'      => 'Le code doit contenir au moins 4 caractères.',
        ]);

        $code = strtoupper(trim($request->code));
        $user = Auth::user();

        // 1. Trouver le ticket
        $ticket = TicketCode::where('code', $code)->first();

        if (!$ticket) {
            return redirect()->route('dashboard')
                ->withErrors(['code' => "Le code « {$code} » n'existe pas."])
                ->with('code_status', "Le code « {$code} » est invalide.")
                ->with('code_status_type', 'error');
        }

        // 2. Vérifier qu'il n'est pas déjà utilisé
        if ($ticket->is_used) {
            return redirect()->route('dashboard')
                ->withErrors(['code' => 'Ce code a déjà été utilisé.'])
                ->with('code_status', 'Ce code a déjà été utilisé.')
                ->with('code_status_type', 'error');
        }

        // 3. Vérifier que l'utilisateur n'a pas déjà participé avec ce ticket
        $alreadyUsed = Participation::where('ticket_code_id', $ticket->id)->exists();
        if ($alreadyUsed) {
            return redirect()->route('dashboard')
                ->withErrors(['code' => 'Ce code a déjà été utilisé.'])
                ->with('code_status', 'Ce code a déjà été utilisé.')
                ->with('code_status_type', 'error');
        }

        // 4. Tirage au sort — transaction pour éviter les race conditions
        DB::beginTransaction();
        try {
            // Verrouiller le ticket
            $ticket = TicketCode::where('id', $ticket->id)
                                 ->lockForUpdate()
                                 ->first();

            if ($ticket->is_used) {
                DB::rollBack();
                return redirect()->route('dashboard')
                    ->with('code_status', 'Ce code vient d\'être utilisé.')
                    ->with('code_status_type', 'error');
            }

            // Tirer un lot disponible au hasard
            $prize = Prize::where('stock', '>', 0)
                          ->lockForUpdate()
                          ->inRandomOrder()
                          ->first();

            // Créer la participation
            $participation = Participation::create([
                'id'                 => Str::uuid(),
                'user_id'            => $user->id,
                'ticket_code_id'     => $ticket->id,
                'prize_id'           => $prize?->id,
                'participation_date' => now(),
            ]);

            // Décrémenter le stock si lot attribué
            if ($prize) {
                $prize->decrement('stock');
            }

            // Marquer le ticket comme utilisé
            $ticket->update(['is_used' => true]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('dashboard')
                ->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }

        // 5. Message de retour
        if ($prize) {
            return redirect()->route('dashboard')
                ->with('participation_success', "🎉 Félicitations ! Vous avez remporté : **{$prize->name}**")
                ->with('code_status', "Félicitations ! Vous avez remporté un lot.")
                ->with('code_status_type', 'success');
        }

        return redirect()->route('dashboard')
            ->with('participation_success', '🍵 Merci pour votre participation ! Pas de lot cette fois.')
            ->with('code_status', 'Participation enregistrée. Pas de lot cette fois-ci.')
            ->with('code_status_type', 'info');
    }

    /**
     * GET /redemption/{id}/create — Formulaire réclamation
     */
    public function createRedemption(string $id)
    {
        $participation = Participation::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('prize')
            ->firstOrFail();

        if (!$participation->prize || $participation->redemption) {
            return redirect()->route('dashboard');
        }

        return view('dashboard.redemption', compact('participation'));
    }

    /**
     * POST /redemption — Enregistrer la réclamation
     */
    public function storeRedemption(Request $request): RedirectResponse
    {
        $request->validate([
            'participation_id' => ['required', 'uuid'],
            'method'           => ['required', 'in:store,mail,online'],
        ]);

        $participation = Participation::where('id', $request->participation_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$participation->prize_id) {
            return redirect()->route('dashboard')
                ->with('error', 'Cette participation n\'est pas gagnante.');
        }

        if ($participation->redemption) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous avez déjà soumis une réclamation pour ce lot.');
        }

        Redemption::create([
            'id'               => Str::uuid(),
            'participation_id' => $participation->id,
            'method'           => $request->method,
            'status'           => 'pending',
            'requested_at'     => now(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', '✅ Votre demande de réclamation a bien été enregistrée !');
    }
}