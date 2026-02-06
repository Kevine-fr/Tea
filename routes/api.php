<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;

Route::get('/', function () {
    return "Server in life...✅";
});

Route::get('/random-status', [DemoController::class, 'randomStatus']);
Route::get('/always-ok', [DemoController::class, 'alwaysOk']);

