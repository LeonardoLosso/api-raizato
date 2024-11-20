<?php
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FornecedorController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StockMovementController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('fornecedores', FornecedorController::class);
    Route::apiResource('categorias', CategoryController::class);
    Route::apiResource('produtos', ProductController::class);
    Route::apiResource('estoque', StockMovementController::class);
    Route::get('estoque/historico/{productId}', [StockMovementController::class, 'historyByProduct']);
});
