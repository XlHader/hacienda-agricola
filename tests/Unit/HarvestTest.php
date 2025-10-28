<?php

namespace Tests\Unit;

use App\Models\Harvest;
use App\Models\PlantingSeason;
use App\Models\ProductBatch;
use App\Models\Plot;
use App\Models\Variety;
use App\Models\Crop;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HarvestTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test: Crear una cosecha con atributos válidos
   */
  public function test_harvest_creation_with_valid_attributes(): void
  {
    $plot = Plot::create([
      'code' => 'PLOT-HARVEST-001',
      'area_hectares' => 4.0,
      'soil_type' => 'Franco-limoso',
      'soil_analysis' => 'Análisis disponible',
    ]);

    $crop = Crop::create([
      'name' => 'Cebada',
      'type' => 'grain',
      'description' => 'Cultivo de cebada',
    ]);
    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Cebada Cervecera',
      'characteristics' => 'Calidad cervecera',
    ]);

    $plantingSeason = PlantingSeason::create([
      'plot_id' => $plot->id,
      'variety_id' => $variety->id,
      'planting_date' => now()->subMonths(6),
      'expected_harvest_date' => now(),
      'status' => 'active',
    ]);

    $harvest = Harvest::create([
      'planting_season_id' => $plantingSeason->id,
      'harvest_date' => now(),
      'quantity_kg' => 5000,
      'quality' => 'first',
    ]);

    $this->assertDatabaseHas('harvests', [
      'planting_season_id' => $plantingSeason->id,
      'quantity_kg' => 5000,
      'quality' => 'first',
    ]);
    $this->assertNotNull($harvest->id);
  }

  /**
   * Test: Verificar relación belongsTo con PlantingSeason
   */
  public function test_harvest_belongs_to_planting_season(): void
  {
    $plot = Plot::create([
      'code' => 'PLOT-HARVEST-002',
      'area_hectares' => 6.0,
      'soil_type' => 'Arenoso',
      'soil_analysis' => 'Análisis suelo',
    ]);

    $crop = Crop::create([
      'name' => 'Sorgo',
      'type' => 'grain',
      'description' => 'Cultivo de sorgo',
    ]);
    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Sorgo Dulce',
      'characteristics' => 'Sorgo azucarado',
    ]);

    $plantingSeason = PlantingSeason::create([
      'plot_id' => $plot->id,
      'variety_id' => $variety->id,
      'planting_date' => now()->subMonths(5),
      'expected_harvest_date' => now(),
      'status' => 'active',
    ]);

    $harvest = Harvest::create([
      'planting_season_id' => $plantingSeason->id,
      'harvest_date' => now(),
      'quantity_kg' => 8000,
      'quality' => 'second',
    ]);
    $this->assertTrue($harvest->plantingSeason()->exists());
    $this->assertEquals($plantingSeason->id, $harvest->plantingSeason->id);
  }

  /**
   * Test: Verificar relación hasMany con ProductBatches
   */
  public function test_harvest_has_many_product_batches(): void
  {
    $plot = Plot::create([
      'code' => 'PLOT-HARVEST-003',
      'area_hectares' => 3.5,
      'soil_type' => 'Franco',
      'soil_analysis' => 'Análisis pH y nutrientes',
    ]);

    $crop = Crop::create([
      'name' => 'Avena',
      'type' => 'grain',
      'description' => 'Cultivo de avena',
    ]);

    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Avena Blanca',
      'characteristics' => 'Alta calidad',
    ]);

    $plantingSeason = PlantingSeason::create([
      'plot_id' => $plot->id,
      'variety_id' => $variety->id,
      'planting_date' => now()->subMonths(4),
      'expected_harvest_date' => now(),
      'status' => 'active',
    ]);

    $harvest = Harvest::create([
      'planting_season_id' => $plantingSeason->id,
      'harvest_date' => now(),
      'quantity_kg' => 3500,
      'quality' => 'first',
    ]);

    // Crear un producto real
    $product = Product::create([
      'name' => 'Avena en Grano',
      'type' => 'Cereal',
      'unit' => 'kg',
    ]);

    $batch1 = ProductBatch::create([
      'product_id' => $product->id,
      'harvest_id' => $harvest->id,
      'batch_number' => 'HARVEST-BATCH-001',
      'quantity' => 1750,
      'production_date' => now(),
    ]);

    $batch2 = ProductBatch::create([
      'product_id' => $product->id,
      'harvest_id' => $harvest->id,
      'batch_number' => 'HARVEST-BATCH-002',
      'quantity' => 1750,
      'production_date' => now()->addHours(1),
    ]);

    $this->assertEquals(2, $harvest->productBatches()->count());
    $this->assertTrue($harvest->productBatches()->where('id', $batch1->id)->exists());
  }
}
