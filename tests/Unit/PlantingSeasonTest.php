<?php

namespace Tests\Unit;

use App\Models\PlantingSeason;
use App\Models\Plot;
use App\Models\Variety;
use App\Models\Crop;
use App\Models\Harvest;
use App\Models\TaskAssignment;
use App\Models\FertilizationPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlantingSeasonTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test: Crear una temporada de siembra con atributos válidos
   */
  public function test_planting_season_creation_with_valid_attributes(): void
  {
    $plot = Plot::create([
      'code' => 'PLOT-001',
      'area_hectares' => 5.5,
      'soil_type' => 'Franco-arenoso',
      'soil_analysis' => 'Análisis completo',
    ]);

    $crop = Crop::create([
      'name' => 'Papá',
      'type' => 'fruit',
      'description' => 'Cultivo de papa',
    ]);

    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Papa Sabanera',
      'characteristics' => 'Alto rendimiento',
    ]);

    $plantingSeason = PlantingSeason::create([
      'plot_id' => $plot->id,
      'variety_id' => $variety->id,
      'planting_date' => now(),
      'expected_harvest_date' => now()->addMonths(4),
      'status' => 'active',
    ]);

    $this->assertDatabaseHas('planting_seasons', [
      'plot_id' => $plot->id,
      'variety_id' => $variety->id,
      'status' => 'active',
    ]);
    $this->assertNotNull($plantingSeason->id);
  }

  /**
   * Test: Verificar relación belongsTo con Plot
   */
  public function test_planting_season_belongs_to_plot(): void
  {
    $plot = Plot::create([
      'code' => 'PLOT-002',
      'area_hectares' => 3.0,
      'soil_type' => 'Arcilloso',
      'soil_analysis' => 'Análisis pH',
    ]);

    $crop = Crop::create([
      'name' => 'Yuca',
      'type' => 'fruit',
      'description' => 'Cultivo de yuca',
    ]);

    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Yuca Industrial',
      'characteristics' => 'Uso industrial',
    ]);

    $plantingSeason = PlantingSeason::create([
      'plot_id' => $plot->id,
      'variety_id' => $variety->id,
      'planting_date' => now(),
      'expected_harvest_date' => now()->addMonths(12),
      'status' => 'active',
    ]);

    $this->assertTrue($plantingSeason->plot()->exists());
    $this->assertEquals($plot->id, $plantingSeason->plot->id);
  }

  /**
   * Test: Verificar relación belongsTo con Variety
   */
  public function test_planting_season_belongs_to_variety(): void
  {
    $plot = Plot::create([
      'code' => 'PLOT-003',
      'area_hectares' => 2.0,
      'soil_type' => 'Franco',
      'soil_analysis' => 'Análisis completo',
    ]);

    $crop = Crop::create([
      'name' => 'Trigo',
      'type' => 'grain',
      'description' => 'Cultivo de trigo',
    ]);

    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Trigo Harinero',
      'characteristics' => 'Para pan',
    ]);

    $plantingSeason = PlantingSeason::create([
      'plot_id' => $plot->id,
      'variety_id' => $variety->id,
      'planting_date' => now(),
      'expected_harvest_date' => now()->addMonths(6),
      'status' => 'active',
    ]);

    $this->assertTrue($plantingSeason->variety()->exists());
    $this->assertEquals($variety->id, $plantingSeason->variety->id);
  }
}
