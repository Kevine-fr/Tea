<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;

Route::get('/', function () {
    return "Server in life...✅";
});

Route::get('/random', [DemoController::class, 'random']);
Route::get('/always', [DemoController::class, 'always']);

