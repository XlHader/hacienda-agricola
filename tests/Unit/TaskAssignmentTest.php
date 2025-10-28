<?php

namespace Tests\Unit;

use App\Models\TaskAssignment;
use App\Models\AgriculturalTask;
use App\Models\PlantingSeason;
use App\Models\Employee;
use App\Models\Plot;
use App\Models\Variety;
use App\Models\Crop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAssignmentTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test: Crear una asignación de tarea con atributos válidos
   */
  public function test_task_assignment_creation_with_valid_attributes(): void
  {
    $task = AgriculturalTask::create([
      'name' => 'Riego',
      'description' => 'Riego de cultivos',
    ]);

    $employee = Employee::create([
      'first_name' => 'Luis',
      'last_name' => 'Sánchez',
      'identification' => '33333333',
      'hire_date' => now(),
      'role' => 'Trabajador',
    ]);

    $plot = Plot::create([
      'code' => 'PLOT-TASK-001',
      'area_hectares' => 2.5,
      'soil_type' => 'Franco',
      'soil_analysis' => 'Análisis suelo',
    ]);

    $crop = Crop::create([
      'name' => 'Café',
      'type' => 'fruit',
      'description' => 'Cultivo de café',
    ]);
    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Café Arábica',
      'characteristics' => 'Grano arábica',
    ]);

    $plantingSeason = PlantingSeason::create([
      'plot_id' => $plot->id,
      'variety_id' => $variety->id,
      'planting_date' => now(),
      'expected_harvest_date' => now()->addYears(2),
      'status' => 'active',
    ]);

    $taskAssignment = TaskAssignment::create([
      'task_id' => $task->id,
      'planting_season_id' => $plantingSeason->id,
      'employee_id' => $employee->id,
      'assigned_date' => now(),
      'completion_date' => now()->addDays(1),
    ]);

    $this->assertDatabaseHas('task_assignments', [
      'task_id' => $task->id,
      'employee_id' => $employee->id,
      'planting_season_id' => $plantingSeason->id,
    ]);
    $this->assertNotNull($taskAssignment->id);
  }

  /**
   * Test: Verificar relación belongsTo con AgriculturalTask
   */
  public function test_task_assignment_belongs_to_agricultural_task(): void
  {
    $task = AgriculturalTask::create([
      'name' => 'Fertilización',
      'description' => 'Aplicación de fertilizante',
    ]);

    $employee = Employee::create([
      'first_name' => 'Elena',
      'last_name' => 'Gómez',
      'identification' => '44444444',
      'hire_date' => now(),
      'role' => 'Técnico',
    ]);

    $plot = Plot::create([
      'code' => 'PLOT-TASK-002',
      'area_hectares' => 3.0,
      'soil_type' => 'Arcilloso',
      'soil_analysis' => 'Análisis pH',
    ]);

    $crop = Crop::create([
      'name' => 'Plátano',
      'type' => 'fruit',
      'description' => 'Cultivo de plátano',
    ]);
    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Plátano Hartón',
      'characteristics' => 'Plátano de cocción',
    ]);

    $plantingSeason = PlantingSeason::create([
      'plot_id' => $plot->id,
      'variety_id' => $variety->id,
      'planting_date' => now(),
      'expected_harvest_date' => now()->addMonths(9),
      'status' => 'active',
    ]);

    $taskAssignment = TaskAssignment::create([
      'task_id' => $task->id,
      'planting_season_id' => $plantingSeason->id,
      'employee_id' => $employee->id,
      'assigned_date' => now(),
      'completion_date' => now()->addDays(2),
    ]);

    $this->assertTrue($taskAssignment->agriculturalTask()->exists());
    $this->assertEquals($task->id, $taskAssignment->agriculturalTask->id);
  }

  /**
   * Test: Verificar relación belongsTo con Employee
   */
  public function test_task_assignment_belongs_to_employee(): void
  {
    $task = AgriculturalTask::create([
      'name' => 'Poda',
      'description' => 'Poda de plantas',
    ]);

    $employee = Employee::create([
      'first_name' => 'Diego',
      'last_name' => 'López',
      'identification' => '55555555',
      'hire_date' => now()->subMonths(3),
      'role' => 'Especialista',
    ]);

    $plot = Plot::create([
      'code' => 'PLOT-TASK-003',
      'area_hectares' => 1.5,
      'soil_type' => 'Franco-arenoso',
      'soil_analysis' => 'Análisis completo',
    ]);

    $crop = Crop::create([
      'name' => 'Cacao',
      'type' => 'fruit',
      'description' => 'Cultivo de cacao',
    ]);
    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Cacao Criollo',
      'characteristics' => 'Cacao fino',
    ]);

    $plantingSeason = PlantingSeason::create([
      'plot_id' => $plot->id,
      'variety_id' => $variety->id,
      'planting_date' => now(),
      'expected_harvest_date' => now()->addYears(3),
      'status' => 'active',
    ]);

    $taskAssignment = TaskAssignment::create([
      'task_id' => $task->id,
      'planting_season_id' => $plantingSeason->id,
      'employee_id' => $employee->id,
      'assigned_date' => now(),
      'completion_date' => now()->addDays(3),
    ]);

    $this->assertTrue($taskAssignment->employee()->exists());
    $this->assertEquals($employee->id, $taskAssignment->employee->id);
  }
}
