<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CsvUploadController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

    Route::get('user', [AuthController::class, 'show']);
    Route::post('upload', [CsvUploadController::class, 'store']);
    Route::get('payments', [PaymentController::class, 'index']);
    Route::get('process', [CsvUploadController::class, 'show']);
});

// Route::get('mail', [CsvUploadController::class, 'email']);
