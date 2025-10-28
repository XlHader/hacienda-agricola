<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\QualityStandard;
use App\Models\Harvest;
use App\Models\PlantingSeason;
use App\Models\Plot;
use App\Models\Crop;
use App\Models\Variety;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test: Crear un producto con atributos válidos
   */
  public function test_product_creation_with_valid_attributes(): void
  {
    $product = Product::create([
      'name' => 'Maíz Blanco',
      'type' => 'Grano',
      'unit' => 'kg',
    ]);

    $this->assertDatabaseHas('products', [
      'name' => 'Maíz Blanco',
      'type' => 'Grano',
      'unit' => 'kg',
    ]);
    $this->assertNotNull($product->id);
  }

  /**
   * Test: Verificar relación hasMany con ProductBatches
   */
  public function test_product_has_many_product_batches(): void
  {
    $product = Product::create([
      'name' => 'Arroz Integral',
      'type' => 'Grano',
      'unit' => 'kg',
    ]);

    // Crear Harvest necesario para ProductBatch
    $plot = Plot::create([
      'code' => 'PLOT-PROD-001',
      'area_hectares' => 3.0,
      'soil_type' => 'Franco',
      'soil_analysis' => 'Análisis disponible',
    ]);

    $crop = Crop::create([
      'name' => 'Arroz',
      'type' => 'grain',
      'description' => 'Cultivo de arroz',
    ]);

    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Arroz Blanco',
      'characteristics' => 'Arroz blanco comercial',
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
      'quantity_kg' => 3000,
      'quality' => 'first',
    ]);

    $batch1 = ProductBatch::create([
      'product_id' => $product->id,
      'harvest_id' => $harvest->id,
      'batch_number' => 'BATCH-001',
      'quantity' => 1000,
      'production_date' => now(),
    ]);

    $batch2 = ProductBatch::create([
      'product_id' => $product->id,
      'harvest_id' => $harvest->id,
      'batch_number' => 'BATCH-002',
      'quantity' => 1500,
      'production_date' => now()->addDays(1),
    ]);

    $this->assertEquals(2, $product->productBatches()->count());
    $this->assertTrue($product->productBatches()->where('id', $batch1->id)->exists());
    $this->assertTrue($product->productBatches()->where('id', $batch2->id)->exists());
  }

  /**
   * Test: Verificar relación hasMany con QualityStandards
   */
  public function test_product_has_many_quality_standards(): void
  {
    $product = Product::create([
      'name' => 'Frijol Rojo',
      'type' => 'Leguminosa',
      'unit' => 'kg',
    ]);

    $standard1 = QualityStandard::create([
      'product_id' => $product->id,
      'grade' => 'first',
      'criteria' => 'Color rojo intenso, sin defectos',
    ]);

    $standard2 = QualityStandard::create([
      'product_id' => $product->id,
      'grade' => 'second',
      'criteria' => 'Color rojo, puede tener pequeños defectos',
    ]);

    $this->assertEquals(2, $product->qualityStandards()->count());
    $this->assertTrue($product->qualityStandards()->where('id', $standard1->id)->exists());
  }
}
