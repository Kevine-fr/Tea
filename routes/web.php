<?php

use App\Http\Controllers\Web\AdminWebController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Thé Tip Top
|--------------------------------------------------------------------------
|
|  Rendu côté serveur avec Laravel Blade
|
*/

// ─── Page d'accueil ────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ─── Authentification (guests uniquement) ──────────────────────────────────────
Route::middleware('guest')->group(function () {
    // Page jeu-concours : login + register
    Route::get('/jeu-concours', [AuthWebController::class, 'showLogin'])->name('login');

    // Traitement login
    Route::post('/login', [AuthWebController::class, 'login'])->name('login.post');

    // Traitement register
    Route::post('/register', [AuthWebController::class, 'register'])->name('register.post');

    // OAuth Google
    Route::get('/auth/google',          [AuthWebController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthWebController::class, 'handleGoogleCallback']);

    // OAuth Facebook
    Route::get('/auth/facebook',          [AuthWebController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('/auth/facebook/callback', [AuthWebController::class, 'handleFacebookCallback']);
});

// ─── Déconnexion ───────────────────────────────────────────────────────────────
Route::post('/logout', [AuthWebController::class, 'logout'])
     ->middleware('auth')
     ->name('logout');

// ─── Espace utilisateur (connecté) ────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',             [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/participate',          [DashboardController::class, 'participate'])->name('participate');
    Route::get('/redemption/{id}/create', [DashboardController::class, 'createRedemption'])->name('redemption.create');
    Route::post('/redemption',           [DashboardController::class, 'storeRedemption'])->name('redemption.store');
});

// ─── Admin + Employee ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin,employee'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    Route::get('/',                              [AdminWebController::class, 'dashboard'])->name('dashboard');
    Route::get('/participations',                [AdminWebController::class, 'participations'])->name('participations');
    Route::get('/redemptions',                   [AdminWebController::class, 'redemptions'])->name('redemptions');
    Route::patch('/redemptions/{id}/status',     [AdminWebController::class, 'updateRedemptionStatus'])->name('redemption.status');
    Route::get('/prizes',                        [AdminWebController::class, 'prizes'])->name('prizes');
    Route::get('/tickets',                       [AdminWebController::class, 'tickets'])->name('tickets');
});

// ─── Admin uniquement ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    Route::post('/prizes',              [AdminWebController::class, 'storePrize'])->name('prizes.store');
    Route::patch('/prizes/{id}',        [AdminWebController::class, 'updatePrize'])->name('prizes.update');
    Route::post('/tickets/generate',    [AdminWebController::class, 'generateTickets'])->name('tickets.generate');
    Route::get('/users',                [AdminWebController::class, 'users'])->name('users');
});