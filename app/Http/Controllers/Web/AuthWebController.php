<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthWebController extends Controller
{
    // ─── Affichage login ──────────────────────────────────────────────────────
    public function showLogin()
    {
        return view('auth.login');
    }

    // ─── Affichage register ───────────────────────────────────────────────────
    public function showRegister()
    {
        return view('auth.register');
    }

    // ─── Traitement login ─────────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Identifiants incorrects. Veuillez réessayer.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($user->isAdmin() || $user->isEmployee()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('dashboard'));
    }

    // ─── Traitement register ──────────────────────────────────────────────────
    public function register(Request $request)
    {
        $validated = $request->validate([
            'last_name'             => ['required', 'string', 'max:100'],
            'first_name'            => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'password'              => ['required', 'confirmed', Password::min(8)],
            'terms'                 => ['required', 'accepted'],
            'birth_date'            => ['nullable', 'date', 'before:-18 years'],
        ], [
            'last_name.required'    => 'Le nom est obligatoire.',
            'first_name.required'   => 'Le prénom est obligatoire.',
            'email.required'        => 'L\'adresse e-mail est obligatoire.',
            'email.unique'          => 'Cette adresse e-mail est déjà utilisée.',
            'password.confirmed'    => 'Les mots de passe ne correspondent pas.',
            'password.min'          => 'Le mot de passe doit contenir au moins 8 caractères.',
            'terms.accepted'        => 'Vous devez accepter les conditions d\'utilisation.',
            'birth_date.before'     => 'Vous devez avoir au moins 18 ans pour participer.',
        ]);

        // Récupérer l'id du rôle "user"
        $userRoleId = Role::where('name', 'user')->value('id');

        $user = User::create([
            'last_name'     => $validated['last_name'],
            'first_name'    => $validated['first_name'],
            'email'         => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
            'role_id'       => $userRoleId,
            'birth_date'    => $validated['birth_date'] ?? null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Bienvenue ' . $user->first_name . ' ! Votre compte a été créé avec succès.');
    }

    // ─── Déconnexion ──────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // ─── OAuth Google (stub) ──────────────────────────────────────────────────
    public function redirectToGoogle()
    {
        return redirect()->route('login')
            ->with('info', 'Connexion Google temporairement indisponible.');
    }

    // ─── OAuth Facebook (stub) ────────────────────────────────────────────────
    public function redirectToFacebook()
    {
        return redirect()->route('login')
            ->with('info', 'Connexion Facebook temporairement indisponible.');
    }
}