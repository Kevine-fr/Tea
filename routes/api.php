<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ParticipationController;
use App\Http\Controllers\Api\PrizeController;
use App\Http\Controllers\Api\RedemptionController;
use App\Http\Controllers\Api\TicketCodeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Thé Tip Top
|--------------------------------------------------------------------------
|
|  Préfixe global : /api
|
|  Auth   : Laravel Sanctum (Bearer token)
|  Rôles  : admin | employee | user
|
*/

// ─── Authentification (public) ─────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me',      [AuthController::class, 'me']);
    });
});

// ─── Lots (public : lecture seule) ─────────────────────────────────────────────
Route::get('/prizes', [PrizeController::class, 'index']);

// ─── Routes authentifiées (tous les rôles) ─────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Participations de l'utilisateur connecté
    Route::get('/participations',  [ParticipationController::class, 'index']);
    Route::post('/participations', [ParticipationController::class, 'store']);

    // Réclamation d'un lot
    Route::post('/redemptions', [RedemptionController::class, 'store']);
});

// ─── Routes Admin + Employee ───────────────────────────────────────────────────
Route::middleware(['auth:sanctum', 'role:admin,employee'])
     ->prefix('admin')
     ->group(function () {

    // Stats globales
    Route::get('/stats',               [ParticipationController::class, 'stats']);

    // Toutes les participations
    Route::get('/participations',      [ParticipationController::class, 'adminIndex']);

    // Gestion des réclamations
    Route::get('/redemptions',                              [RedemptionController::class, 'pending']);
    Route::patch('/redemptions/{id}/status',                [RedemptionController::class, 'updateStatus']);

    // Stats tickets
    Route::get('/tickets/stats',       [TicketCodeController::class, 'stats']);
});

// ─── Routes Admin uniquement ───────────────────────────────────────────────────
Route::middleware(['auth:sanctum', 'role:admin'])
     ->prefix('admin')
     ->group(function () {

    // Gestion des lots
    Route::post('/prizes',           [PrizeController::class, 'store']);
    Route::put('/prizes/{id}',       [PrizeController::class, 'update']);
    Route::delete('/prizes/{id}',    [PrizeController::class, 'destroy']);

    // Génération de tickets
    Route::post('/tickets/generate', [TicketCodeController::class, 'generate']);
});