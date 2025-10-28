<?php

namespace Tests\Unit;

use App\Models\Variety;
use App\Models\Crop;
use App\Models\PlantingSeason;
use App\Models\Plot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VarietyTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test: Crear una variedad con atributos válidos
   */
  public function test_variety_creation_with_valid_attributes(): void
  {
    $crop = Crop::create([
      'name' => 'Lechuga',
      'type' => 'vegetable',
      'description' => 'Cultivo de lechuga',
    ]);

    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Lechuga Criolla',
      'characteristics' => 'Hojas grandes y tiernas',
    ]);

    $this->assertDatabaseHas('varieties', [
      'crop_id' => $crop->id,
      'name' => 'Lechuga Criolla',
    ]);
    $this->assertNotNull($variety->id);
  }

  /**
   * Test: Verificar relación belongsTo con Crop
   */
  public function test_variety_belongs_to_crop(): void
  {
    $crop = Crop::create([
      'name' => 'Zanahoria',
      'type' => 'vegetable',
      'description' => 'Cultivo de zanahoria',
    ]);

    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Zanahoria Naranja',
      'characteristics' => 'Color naranja intenso',
    ]);

    $this->assertTrue($variety->crop()->exists());
    $this->assertEquals($crop->id, $variety->crop->id);
    $this->assertEquals('Zanahoria', $variety->crop->name);
  }

  /**
   * Test: Verificar relación hasMany con PlantingSeasons
   */
  public function test_variety_has_many_planting_seasons(): void
  {
    $crop = Crop::create([
      'name' => 'Cebolla',
      'type' => 'vegetable',
      'description' => 'Cultivo de cebolla',
    ]);
    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Cebolla Blanca',
      'characteristics' => 'Cebolla blanca dulce',
    ]);

    $plot1 = Plot::create([
      'code' => 'PLOT-VAR-001',
      'area_hectares' => 2.0,
      'soil_type' => 'Franco',
      'soil_analysis' => 'Análisis disponible',
    ]);

    $plot2 = Plot::create([
      'code' => 'PLOT-VAR-002',
      'area_hectares' => 1.5,
      'soil_type' => 'Franco-limoso',
      'soil_analysis' => 'Análisis disponible',
    ]);

    $season1 = PlantingSeason::create([
      'plot_id' => $plot1->id,
      'variety_id' => $variety->id,
      'planting_date' => now(),
      'expected_harvest_date' => now()->addMonths(4),
      'status' => 'active',
    ]);

    $season2 = PlantingSeason::create([
      'plot_id' => $plot2->id,
      'variety_id' => $variety->id,
      'planting_date' => now()->addDays(7),
      'expected_harvest_date' => now()->addMonths(5),
      'status' => 'active',
    ]);

    $this->assertEquals(2, $variety->plantingSeasons()->count());
    $this->assertTrue($variety->plantingSeasons()->where('id', $season1->id)->exists());
    $this->assertTrue($variety->plantingSeasons()->where('id', $season2->id)->exists());
  }
}
