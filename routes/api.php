<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlotController;

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
  Route::get('/plots', [PlotController::class, 'index']);          // Tarea 2.2
  Route::post('/plots', [PlotController::class, 'store']);  // Tarea 2.3
  Route::get('/plots/{plot}', [PlotController::class, 'show']);   //Tarea 2.4


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
