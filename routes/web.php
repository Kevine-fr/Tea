<?php

use Illuminate\Support\Facades\Route;
use App\Services\MetricsService;

Route::get('/metrics', function () {
    return response(
        MetricsService::export(),
        200,
        ['Content-Type' => 'text/plain; version=0.0.4']
    );
});

Route::get('/', function () {
    return view('welcome');
});