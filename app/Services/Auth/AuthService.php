<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
  /**
   * Register a new user and issue an access token.
   *
   * @param array<string, mixed> $data
   * @return array{user: \App\Models\User, token: string}
   */
  public function register(array $data): array
  {
    $payload = [
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
    ];

    if (array_key_exists('employee_id', $data)) {
      $payload['employee_id'] = $data['employee_id'];
    }

    $user = User::create($payload);

    return [
      'user' => $user->fresh(),
      'token' => $this->issueToken($user),
    ];
  }

  /**
   * Authenticate an existing user and issue an access token.
   *
   * @param array{email: string, password: string} $credentials
   * @return array{user: \App\Models\User, token: string}
   */
  public function login(array $credentials): array
  {
    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
      throw new AuthenticationException(__('auth.failed'));
    }

    return [
      'user' => $user,
      'token' => $this->issueToken($user),
    ];
  }

  /**
   * Revoke the current access token for the authenticated user.
   */
  public function logout(User $user): void
  {
    $user->currentAccessToken()?->delete();
  }

  /**
   * Revoke every access token issued to the user.
   */
  public function logoutAll(User $user): void
  {
    $user->tokens()->delete();
  }

  /**
   * Refresh credentials by revoking the current token and issuing a new one.
   */
  public function refresh(User $user): string
  {
    $this->logout($user);

    return $this->issueToken($user);
  }

  private function issueToken(User $user, string $tokenName = 'api-token'): string
  {
    // Keep a single active token per user/client by removing tokens with the same name.
    $user->tokens()->where('name', $tokenName)->delete();

    return $user->createToken($tokenName)->plainTextToken;
  }
}
