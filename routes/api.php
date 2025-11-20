<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PlotController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
  Route::get('/health', function () {
    return response()->json(['status' => 'API is healthy'], 200);
  });

  Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
  });

  Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);

    Route::apiResource('plots', PlotController::class);

    /**
     * Rutas de gestión de clientes (HU-005)
     * Contexto: Venta y Distribución
     */
    Route::apiResource('customers', CustomerController::class);
    Route::patch('customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])
      ->name('customers.toggle-status');
  });
});
