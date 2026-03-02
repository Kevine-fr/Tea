<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Participation;
use App\Models\Redemption;
use App\Models\TicketCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    // ─── Tableau de bord ──────────────────────────────────────────────────────
    public function index()
    {
        $participations = Participation::with(['ticketCode', 'prize', 'redemption'])
            ->where('user_id', Auth::id())
            ->latest('participation_date')
            ->paginate(10);

        return view('dashboard.index', compact('participations'));
    }

    // ─── Participer ───────────────────────────────────────────────────────────
    public function participate(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'min:4', 'max:20'],
        ], [
            'code.required' => 'Veuillez entrer un code ticket.',
            'code.min'      => 'Le code doit comporter au moins 4 caractères.',
        ]);

        $code = strtoupper(trim($request->code));

        try {
            DB::beginTransaction();

            // Vérifier que le ticket existe et n'est pas utilisé
            $ticket = TicketCode::where('code', $code)
                ->where('is_used', false)
                ->lockForUpdate()
                ->first();

            if (!$ticket) {
                DB::rollBack();
                return back()->withErrors(['code' => 'Code invalide ou déjà utilisé.'])->withInput();
            }

            // Vérifier que l'utilisateur n'a pas déjà participé avec ce code
            $alreadyParticipated = Participation::where('user_id', Auth::id())
                ->where('ticket_code_id', $ticket->id)
                ->exists();

            if ($alreadyParticipated) {
                DB::rollBack();
                return back()->withErrors(['code' => 'Vous avez déjà utilisé ce code.'])->withInput();
            }

            // Sélectionner un lot aléatoire disponible
            $prize = \App\Models\Prize::where('stock', '>', 0)
                ->lockForUpdate()
                ->inRandomOrder()
                ->first();

            // Créer la participation
            $participation = Participation::create([
                'user_id'            => Auth::id(),
                'ticket_code_id'     => $ticket->id,
                'prize_id'           => $prize?->id,
                'participation_date' => now(),
            ]);

            // Marquer le ticket comme utilisé
            $ticket->update(['is_used' => true]);

            // Décrémenter le stock du lot
            if ($prize) {
                $prize->decrement('stock');
            }

            DB::commit();

            if ($prize) {
                return redirect()->route('dashboard')
                    ->with('participation_success', '🎉 Félicitations ! Vous avez gagné : ' . $prize->name . ' !');
            }

            return redirect()->route('dashboard')
                ->with('success', 'Code validé ! Résultat en attente d\'attribution.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['code' => 'Une erreur est survenue. Veuillez réessayer.'])->withInput();
        }
    }

    // ─── Suivi des gains ──────────────────────────────────────────────────────
    public function gains()
    {
        $gains = Participation::with(['ticketCode', 'prize', 'redemption'])
            ->where('user_id', Auth::id())
            ->whereNotNull('prize_id')
            ->latest('participation_date')
            ->paginate(15);

        return view('dashboard.gains', compact('gains'));
    }

    // ─── Profil — affichage ───────────────────────────────────────────────────
    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }

    // ─── Profil — mise à jour ─────────────────────────────────────────────────
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'last_name'  => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'unique:users,email,' . $user->id],
        ];

        // Changement de mot de passe optionnel
        if ($request->filled('current_password') || $request->filled('new_password')) {
            $rules['current_password'] = ['required', 'string'];
            $rules['new_password']     = ['required', 'string', 'min:8', 'confirmed'];
        }

        $validated = $request->validate($rules, [
            'email.unique'               => 'Cette adresse e-mail est déjà utilisée.',
            'current_password.required'  => 'L\'ancien mot de passe est requis pour le changer.',
            'new_password.min'           => 'Le nouveau mot de passe doit faire au moins 8 caractères.',
            'new_password.confirmed'     => 'Les mots de passe ne correspondent pas.',
        ]);

        // Vérifier l'ancien mot de passe si changement demandé
        if (isset($validated['current_password'])) {
            if (!Hash::check($validated['current_password'], $user->password_hash)) {
                return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
            }
            $user->password_hash = Hash::make($validated['new_password']);
        }

        $user->last_name  = $validated['last_name'];
        $user->first_name = $validated['first_name'];
        $user->email      = $validated['email'];
        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    // ─── Suppression de compte ────────────────────────────────────────────────
    public function deleteAccount(Request $request)
    {
        $request->validate(['password' => ['required', 'string']]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password_hash)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Votre compte a été supprimé définitivement.');
    }

    // ─── Réclamation — formulaire ─────────────────────────────────────────────
    public function createRedemption(string $id)
    {
        $participation = Participation::with(['prize', 'ticketCode'])
            ->where('user_id', Auth::id())
            ->whereNotNull('prize_id')
            ->whereDoesntHave('redemption')
            ->findOrFail($id);

        return view('dashboard.redemption', compact('participation'));
    }

    // ─── Réclamation — enregistrement ─────────────────────────────────────────
    public function storeRedemption(Request $request)
    {
        $request->validate([
            'participation_id' => ['required', 'uuid'],
            'method'           => ['required', 'in:store,mail,online'],
        ]);

        $participation = Participation::where('user_id', Auth::id())
            ->whereNotNull('prize_id')
            ->whereDoesntHave('redemption')
            ->findOrFail($request->participation_id);

        Redemption::create([
            'participation_id' => $participation->id,
            'method'           => $request->method,
            'status'           => 'pending',
        ]);

        return redirect()->route('dashboard.gains')
            ->with('success', 'Votre demande de réclamation a été enregistrée !');
    }
}