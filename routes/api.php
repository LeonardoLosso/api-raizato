<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::post('/login', [AuthController::class, 'login']);
});
