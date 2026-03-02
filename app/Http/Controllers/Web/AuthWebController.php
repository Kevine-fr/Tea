<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AuthProvider;
use App\Models\User;
use App\Models\UserAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthWebController extends Controller
{
    /**
     * GET /jeu-concours — Affiche login + register
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * POST /login
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Chercher l'utilisateur manuellement (notre champ est password_hash)
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return back()
                ->withErrors(['login' => 'Email ou mot de passe incorrect.'])
                ->withInput($request->only('email'));
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Rediriger selon le rôle
        if ($user->isAdmin() || $user->isEmployee()) {
            return redirect()->route('admin.dashboard')
                             ->with('success', 'Bienvenue dans l\'espace admin !');
        }

        return redirect()->route('dashboard')
                         ->with('success', 'Bienvenue sur votre espace Thé Tip Top !');
    }

    /**
     * POST /register
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'last_name'             => ['required', 'string', 'max:100'],
            'first_name'            => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'password'              => ['required', 'min:8', 'confirmed'],
            'birth_date'            => ['nullable', 'date', 'before:-18 years'],
            'terms'                 => ['required', 'accepted'],
        ], [
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'birth_date.before'  => 'Vous devez avoir au moins 18 ans pour participer.',
            'terms.required'     => 'Vous devez accepter le règlement du jeu.',
            'terms.accepted'     => 'Vous devez accepter le règlement du jeu.',
        ]);

        $user = User::create([
            'id'            => Str::uuid(),
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password),
            'birth_date'    => $request->birth_date,
            'role_id'       => 3, // user
        ]);

        // Créer l'auth locale
        UserAuth::create([
            'id'               => Str::uuid(),
            'user_id'          => $user->id,
            'provider_id'      => AuthProvider::LOCAL,
            'provider_user_id' => $user->id,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
                         ->with('success', '🎉 Compte créé ! Bienvenue dans le jeu Thé Tip Top.');
    }

    /**
     * POST /logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
                         ->with('success', 'Vous avez été déconnecté.');
    }

    /**
     * OAuth Google — redirect
     */
    public function redirectToGoogle()
    {
        return redirect()->route('login')
                         ->with('error', 'Connexion Google non configurée.');
    }

    /**
     * OAuth Facebook — redirect
     */
    public function redirectToFacebook()
    {
        return redirect()->route('login')
                         ->with('error', 'Connexion Facebook non configurée.');
    }
}