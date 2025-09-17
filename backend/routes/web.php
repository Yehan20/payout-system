<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('login', [AuthController::class, 'store'])->middleware('throttle:api');
Route::post('logout', [AuthController::class, 'destroy'])->middleware('auth:sanctum');
