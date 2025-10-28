<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test: Crear un usuario con atributos válidos
   */
  public function test_user_creation_with_valid_attributes(): void
  {
    $user = User::create([
      'name' => 'Juan Pérez',
      'email' => 'juan@example.com',
      'password' => 'password123',
    ]);

    $this->assertDatabaseHas('users', [
      'name' => 'Juan Pérez',
      'email' => 'juan@example.com',
    ]);
    $this->assertNotNull($user->id);
  }

  /**
   * Test: Verificar relación hasOne con Employee
   */
  public function test_user_has_one_employee_relationship(): void
  {
    $employee = Employee::create([
      'first_name' => 'Carlos',
      'last_name' => 'López',
      'identification' => '12345678',
      'hire_date' => now()->subYear(),
      'role' => 'Manager',
    ]);

    $user = User::create([
      'employee_id' => $employee->id,
      'name' => 'Carlos López',
      'email' => 'carlos@example.com',
      'password' => 'password123',
    ]);

    $this->assertTrue($user->employee()->exists());
    $this->assertEquals($employee->id, $user->employee->id);
  }

  /**
   * Test: Verificar que el password está hasheado
   */
  public function test_user_password_is_hashed(): void
  {
    $user = User::create([
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password' => 'plainpassword123',
    ]);

    $this->assertNotEquals('plainpassword123', $user->password);
    $this->assertTrue(\Illuminate\Support\Facades\Hash::check('plainpassword123', $user->password));
  }
}
