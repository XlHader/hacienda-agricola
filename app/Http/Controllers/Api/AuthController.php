<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
  public function __construct(private AuthService $authService) {}

  public function register(RegisterRequest $request): JsonResponse
  {
    $result = $this->authService->register($request->validated());

    return response()->json([
      'user' => new UserResource($result['user']),
      'token' => $result['token'],
    ], Response::HTTP_CREATED);
  }

  public function login(LoginRequest $request): JsonResponse
  {
    $result = $this->authService->login($request->validated());

    return response()->json([
      'user' => new UserResource($result['user']),
      'token' => $result['token'],
    ]);
  }

  public function me(Request $request): UserResource
  {
    return new UserResource($request->user());
  }

  public function logout(Request $request): JsonResponse
  {
    $this->authService->logout($request->user());

    return response()->json([
      'message' => 'Sesión cerrada correctamente.',
    ]);
  }

  public function refresh(Request $request): JsonResponse
  {
    $token = $this->authService->refresh($request->user());

    return response()->json([
      'token' => $token,
    ]);
  }
}
