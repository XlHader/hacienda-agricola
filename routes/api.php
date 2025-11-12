<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Rutas públicas de la API
 * Prefijo: /api/v1
 */
Route::prefix('v1')->group(function () {
  Route::get('/health', function () {
    return response()->json(['status' => 'API is healthy'], 200);
  });

  // Aquí irán las rutas públicas del API
  // Ejemplo:
  // Route::post('/auth/login', [AuthController::class, 'login']);
  // Route::post('/auth/register', [AuthController::class, 'register']);
});

/**
 * Rutas protegidas de la API (requieren autenticación con Sanctum)
 * Prefijo: /api/v1
 */
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
  Route::get(
    '/user',
    fn(Request $request) =>
    $request->user()
  );

  // Aquí irán las rutas protegidas del API
  // Ejemplo:
  // Route::apiResource('products', ProductController::class);
  // Route::apiResource('customers', CustomerController::class);
  // Route::apiResource('orders', OrderController::class);
  // Route::post('/auth/logout', [AuthController::class, 'logout']);
});
