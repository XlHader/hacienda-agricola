<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\Skill;
use App\Models\Training;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test: Crear un empleado con atributos válidos
   */
  public function test_employee_creation_with_valid_attributes(): void
  {
    $employee = Employee::create([
      'first_name' => 'María',
      'last_name' => 'Rodríguez',
      'identification' => '87654321',
      'hire_date' => now()->subMonths(6),
      'role' => 'Agrónomo',
    ]);

    $this->assertDatabaseHas('employees', [
      'first_name' => 'María',
      'last_name' => 'Rodríguez',
      'identification' => '87654321',
    ]);
    $this->assertNotNull($employee->id);
  }

  /**
   * Test: Verificar relación belongsToMany con Skills
   */
  public function test_employee_belongs_to_many_skills(): void
  {
    $employee = Employee::create([
      'first_name' => 'Pedro',
      'last_name' => 'García',
      'identification' => '11111111',
      'hire_date' => now(),
      'role' => 'Técnico',
    ]);

    $skill1 = Skill::create(['name' => 'Riego', 'description' => 'Técnica de riego']);
    $skill2 = Skill::create(['name' => 'Poda', 'description' => 'Técnica de poda']);

    $employee->skills()->attach($skill1->id, ['certification_date' => now()]);
    $employee->skills()->attach($skill2->id, ['certification_date' => now()]);

    $this->assertEquals(2, $employee->skills()->count());
    $this->assertTrue($employee->skills()->where('skills.id', $skill1->id)->exists());
    $this->assertTrue($employee->skills()->where('skills.id', $skill2->id)->exists());
  }

  /**
   * Test: Verificar relación belongsToMany con Trainings
   */
  public function test_employee_belongs_to_many_trainings(): void
  {
    $employee = Employee::create([
      'first_name' => 'Ana',
      'last_name' => 'Martínez',
      'identification' => '22222222',
      'hire_date' => now(),
      'role' => 'Trabajador',
    ]);

    $training = Training::create([
      'name' => 'Seguridad Agrícola',
      'description' => 'Capacitación sobre seguridad',
      'start_date' => now(),
      'end_date' => now()->addDays(5),
    ]);

    $employee->trainings()->attach($training->id, ['completion_status' => 'completed']);

    $this->assertTrue($employee->trainings()->where('trainings.id', $training->id)->exists());
    $this->assertEquals('completed', $employee->trainings()->where('trainings.id', $training->id)->first()->pivot->completion_status);
  }
}
