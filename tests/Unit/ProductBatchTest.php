<?php

namespace Tests\Unit;

use App\Models\ProductBatch;
use App\Models\Product;
use App\Models\Harvest;
use App\Models\PlantingSeason;
use App\Models\Plot;
use App\Models\Variety;
use App\Models\Crop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductBatchTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test: Crear un lote de producto con atributos válidos
   */
  public function test_product_batch_creation_with_valid_attributes(): void
  {
    $product = Product::create([
      'name' => 'Banano Orgánico',
      'type' => 'Fruta',
      'unit' => 'kg',
    ]);

    $plot = Plot::create([
      'code' => 'PLOT-BATCH-001',
      'area_hectares' => 5.0,
      'soil_type' => 'Franco',
      'soil_analysis' => 'Análisis orgánico',
    ]);

    $crop = Crop::create([
      'name' => 'Banano',
      'type' => 'fruit',
      'description' => 'Cultivo de banano',
    ]);
    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Banano Cavendish',
      'characteristics' => 'Banano comercial',
    ]);

    $plantingSeason = PlantingSeason::create([
      'plot_id' => $plot->id,
      'variety_id' => $variety->id,
      'planting_date' => now()->subMonths(12),
      'expected_harvest_date' => now(),
      'status' => 'harvested',
    ]);

    $harvest = Harvest::create([
      'planting_season_id' => $plantingSeason->id,
      'harvest_date' => now(),
      'quantity_kg' => 10000,
      'quality' => 'first',
    ]);

    $productBatch = ProductBatch::create([
      'product_id' => $product->id,
      'harvest_id' => $harvest->id,
      'batch_number' => 'BATCH-BANANO-001',
      'quantity' => 10000,
      'production_date' => now(),
    ]);

    $this->assertDatabaseHas('product_batches', [
      'product_id' => $product->id,
      'harvest_id' => $harvest->id,
      'batch_number' => 'BATCH-BANANO-001',
    ]);
    $this->assertNotNull($productBatch->id);
  }

  /**
   * Test: Verificar relación belongsTo con Product
   */
  public function test_product_batch_belongs_to_product(): void
  {
    $product = Product::create([
      'name' => 'Piña Dorada',
      'type' => 'Fruta',
      'unit' => 'unidad',
    ]);

    $plot = Plot::create([
      'code' => 'PLOT-BATCH-002',
      'area_hectares' => 4.0,
      'soil_type' => 'Arenoso',
      'soil_analysis' => 'Análisis drenaje',
    ]);

    $crop = Crop::create([
      'name' => 'Piña',
      'type' => 'fruit',
      'description' => 'Cultivo de piña',
    ]);
    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Piña MD2',
      'characteristics' => 'Variedad dorada',
    ]);

    $plantingSeason = PlantingSeason::create([
      'plot_id' => $plot->id,
      'variety_id' => $variety->id,
      'planting_date' => now()->subMonths(18),
      'expected_harvest_date' => now(),
      'status' => 'harvested',
    ]);
    $harvest = Harvest::create([
      'planting_season_id' => $plantingSeason->id,
      'harvest_date' => now(),
      'quantity_kg' => 5000,
      'quality' => 'first',
    ]);
    $productBatch = ProductBatch::create([
      'product_id' => $product->id,
      'harvest_id' => $harvest->id,
      'batch_number' => 'BATCH-PINA-001',
      'quantity' => 5000,
      'production_date' => now(),
    ]);

    $this->assertTrue($productBatch->product()->exists());
    $this->assertEquals($product->id, $productBatch->product->id);
  }

  /**
   * Test: Verificar relación belongsTo con Harvest
   */
  public function test_product_batch_belongs_to_harvest(): void
  {
    $product = Product::create([
      'name' => 'Mango',
      'type' => 'Fruta',
      'unit' => 'kg',
    ]);

    $plot = Plot::create([
      'code' => 'PLOT-BATCH-003',
      'area_hectares' => 6.0,
      'soil_type' => 'Franco-arcilloso',
      'soil_analysis' => 'Análisis completo',
    ]);

    $crop = Crop::create([
      'name' => 'Mango',
      'type' => 'fruit',
      'description' => 'Cultivo de mango',
    ]);
    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Mango Tommy Atkins',
      'characteristics' => 'Mango exportación',
    ]);

    $plantingSeason = PlantingSeason::create([
      'plot_id' => $plot->id,
      'variety_id' => $variety->id,
      'planting_date' => now()->subMonths(24),
      'expected_harvest_date' => now(),
      'status' => 'harvested',
    ]);
    $harvest = Harvest::create([
      'planting_season_id' => $plantingSeason->id,
      'harvest_date' => now(),
      'quantity_kg' => 8000,
      'quality' => 'first',
    ]);
    $productBatch = ProductBatch::create([
      'product_id' => $product->id,
      'harvest_id' => $harvest->id,
      'batch_number' => 'BATCH-MANGO-001',
      'quantity' => 8000,
      'production_date' => now(),
    ]);

    $this->assertTrue($productBatch->harvest()->exists());
    $this->assertEquals($harvest->id, $productBatch->harvest->id);
  }
}
