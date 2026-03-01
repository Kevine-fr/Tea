<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\AuthProvider;
use App\Models\User;
use App\Models\UserAuth;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthWebController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    /**
     * GET /jeu-concours
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
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        // Laravel Auth utilise "password" mais notre champ est "password_hash"
        // On utilise notre service custom
        try {
            $result = $this->authService->login($request->email, $request->password);
            Auth::login($result['user'], $request->boolean('remember'));

            return redirect()->intended(route('dashboard'))
                             ->with('success', 'Bienvenue sur votre espace Thé Tip Top !');
        } catch (\App\Exceptions\AppException $e) {
            return back()->withErrors(['login' => $e->getMessage()])->withInput();
        }
    }

    /**
     * POST /register
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'last_name'             => ['required', 'string', 'max:100'],
            'first_name'            => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'unique:users,email', 'max:255'],
            'password'              => ['required', 'min:8', 'confirmed'],
            'birth_date'            => ['nullable', 'date', 'before:-18 years'],
            'terms'                 => ['required', 'accepted'],
        ], [
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'birth_date.before'  => 'Vous devez avoir au moins 18 ans pour participer.',
            'terms.required'     => 'Vous devez accepter le règlement du jeu.',
        ]);

        try {
            $result = $this->authService->register([
                'email'      => $validated['email'],
                'password'   => $validated['password'],
                'birth_date' => $validated['birth_date'] ?? null,
            ]);

            Auth::login($result['user']);

            return redirect()->route('dashboard')
                             ->with('success', '🎉 Compte créé avec succès ! Bienvenue dans le jeu Thé Tip Top !');
        } catch (\Exception $e) {
            return back()->withErrors(['register' => $e->getMessage()])->withInput();
        }
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
     * GET /auth/google/redirect
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * GET /auth/google/callback
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $result     = $this->authService->loginOrCreateOAuth(
                AuthProvider::GOOGLE,
                $googleUser->getId(),
                $googleUser->getEmail()
            );
            Auth::login($result['user']);
            return redirect()->route('dashboard')->with('success', 'Connexion Google réussie !');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Erreur de connexion avec Google.');
        }
    }

    /**
     * GET /auth/facebook/redirect
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * GET /auth/facebook/callback
     */
    public function handleFacebookCallback(): RedirectResponse
    {
        try {
            $fbUser = Socialite::driver('facebook')->user();
            $result = $this->authService->loginOrCreateOAuth(
                AuthProvider::FACEBOOK,
                $fbUser->getId(),
                $fbUser->getEmail()
            );
            Auth::login($result['user']);
            return redirect()->route('dashboard')->with('success', 'Connexion Facebook réussie !');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Erreur de connexion avec Facebook.');
        }
    }
}