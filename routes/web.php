<?php

use App\Http\Controllers\Web\AdminWebController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Thé Tip Top
|--------------------------------------------------------------------------
*/

// ─── Page d'accueil ────────────────────────────────────────────────────────────
Route::get('/', [PageController::class, 'home'])->name('home');

// ─── Pages statiques ──────────────────────────────────────────────────────────
Route::get('/jeu',                              [PageController::class, 'jeu'])->name('pages.jeu');
Route::get('/gain',                             [PageController::class, 'gain'])->name('pages.gain');
Route::get('/contact',                          [PageController::class, 'contact'])->name('pages.contact');
Route::post('/contact',                         [PageController::class, 'sendContact'])->name('pages.contact.send');
Route::get('/politique-de-confidentialite',     [PageController::class, 'politique'])->name('pages.politique');
Route::get('/conditions-generales-vente',       [PageController::class, 'cgv'])->name('pages.cgv');
Route::get('/conditions-generales-utilisation', [PageController::class, 'cgu'])->name('pages.cgu');

// ─── Authentification (guests uniquement) ──────────────────────────────────────
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/connexion',  [AuthWebController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthWebController::class, 'login'])->name('login.post');

    // Register — GET et POST utilisent le même nom 'register' dans les vues
    Route::get('/inscription',  [AuthWebController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthWebController::class, 'register'])->name('register.post');

    // OAuth
    Route::get('/auth/google',   [AuthWebController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/facebook', [AuthWebController::class, 'redirectToFacebook'])->name('auth.facebook');
});

// ─── Déconnexion ───────────────────────────────────────────────────────────────
Route::post('/deconnexion', [AuthWebController::class, 'logout'])
     ->middleware('auth')
     ->name('logout');

// ─── Mot de passe oublié (stub) ────────────────────────────────────────────────
Route::get('/mot-de-passe-oublie', function () {
    return redirect()->route('login')->with('info', 'Fonctionnalité à venir.');
})->name('password.request');

// ─── Espace utilisateur (connecté) ────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Tableau de bord principal
    Route::get('/tableau-de-bord', [DashboardController::class, 'index'])->name('dashboard');

    // Participation
    Route::post('/participer', [DashboardController::class, 'participate'])->name('participate');

    // Suivi des gains
    Route::get('/mes-gains', [DashboardController::class, 'gains'])->name('dashboard.gains');

    // Profil
    Route::get('/mon-profil',     [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::patch('/mon-profil',   [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::delete('/mon-profil',  [DashboardController::class, 'deleteAccount'])->name('dashboard.profile.delete');

    // Réclamation de lot
    Route::get('/reclamer/{id}/formulaire', [DashboardController::class, 'createRedemption'])->name('redemption.create');
    Route::post('/reclamer',               [DashboardController::class, 'storeRedemption'])->name('redemption.store');
});

// ─── Admin + Employee ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin,employee'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

         Route::get('/', [AdminWebController::class, 'dashboard'])->name('dashboard');

         Route::get('/participations',             [AdminWebController::class, 'participations'])->name('participations');
         Route::get('/remises',                    [AdminWebController::class, 'redemptions'])->name('redemptions');
         Route::patch('/remises/{id}/statut',      [AdminWebController::class, 'updateRedemptionStatus'])->name('redemption.status');

         Route::get('/lots',                       [AdminWebController::class, 'prizes'])->name('prizes');
         Route::get('/tickets',                    [AdminWebController::class, 'tickets'])->name('tickets');

         // Tickets & Gains (vue combinée — Image 5)
         Route::get('/tickets-gains',              [AdminWebController::class, 'ticketsGains'])->name('tickets-gains');
         Route::patch('/tickets-gains/{id}',       [AdminWebController::class, 'updateTicketGainStatus'])->name('tickets-gains.update');

         // Export CSV
         Route::get('/export/csv', [AdminWebController::class, 'exportCsv'])->name('export-csv');
     });

// ─── Admin uniquement ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {
         Route::post('/lots',           [AdminWebController::class, 'storePrize'])->name('prizes.store');
         Route::patch('/lots/{id}',     [AdminWebController::class, 'updatePrize'])->name('prizes.update');
         Route::post('/tickets/generer',[AdminWebController::class, 'generateTickets'])->name('tickets.generate');
         Route::get('/utilisateurs',    [AdminWebController::class, 'users'])->name('users');
     });