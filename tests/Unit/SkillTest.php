<?php

namespace Tests\Unit;

use App\Models\Skill;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test: Crear una habilidad con atributos válidos
   */
  public function test_skill_creation_with_valid_attributes(): void
  {
    $skill = Skill::create([
      'name' => 'Manejo de Tractores',
      'description' => 'Operación y mantenimiento de tractores',
    ]);

    $this->assertDatabaseHas('skills', [
      'name' => 'Manejo de Tractores',
      'description' => 'Operación y mantenimiento de tractores',
    ]);
    $this->assertNotNull($skill->id);
  }

  /**
   * Test: Verificar relación belongsToMany con Employees
   */
  public function test_skill_belongs_to_many_employees(): void
  {
    $skill = Skill::create([
      'name' => 'Aplicación de Pesticidas',
      'description' => 'Uso seguro de pesticidas',
    ]);

    $employee1 = Employee::create([
      'first_name' => 'Roberto',
      'last_name' => 'García',
      'identification' => '66666666',
      'hire_date' => now(),
      'role' => 'Trabajador',
    ]);

    $employee2 = Employee::create([
      'first_name' => 'Sofía',
      'last_name' => 'Martínez',
      'identification' => '77777777',
      'hire_date' => now(),
      'role' => 'Técnico',
    ]);

    $skill->employees()->attach($employee1->id, ['certification_date' => now()->subMonths(6)]);
    $skill->employees()->attach($employee2->id, ['certification_date' => now()->subMonths(3)]);

    $this->assertEquals(2, $skill->employees()->count());
    $this->assertTrue($skill->employees()->where('employees.id', $employee1->id)->exists());
    $this->assertTrue($skill->employees()->where('employees.id', $employee2->id)->exists());
  }

  /**
   * Test: Verificar datos del pivot en relación belongsToMany
   */
  public function test_skill_pivot_data_in_many_to_many_relationship(): void
  {
    $skill = Skill::create([
      'name' => 'Diseño de Riego',
      'description' => 'Diseño de sistemas de riego',
    ]);

    $employee = Employee::create([
      'first_name' => 'Francisco',
      'last_name' => 'Rodríguez',
      'identification' => '88888888',
      'hire_date' => now(),
      'role' => 'Ingeniero',
    ]);

    $certificationDate = now()->subMonths(1);
    $skill->employees()->attach($employee->id, ['certification_date' => $certificationDate]);

    $attachedEmployee = $skill->employees()->where('employees.id', $employee->id)->first();

    $this->assertNotNull($attachedEmployee->pivot);
    // Verificar que la fecha de certificación está presente
    $this->assertNotNull($attachedEmployee->pivot->certification_date);
  }
}
