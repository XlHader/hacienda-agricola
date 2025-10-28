<?php

namespace Tests\Unit;

use App\Models\Crop;
use App\Models\Variety;
use App\Models\PhenologicalStage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CropTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test: Crear un cultivo con atributos válidos
   */
  public function test_crop_creation_with_valid_attributes(): void
  {
    $crop = Crop::create([
      'name' => 'Maíz',
      'type' => 'grain',
      'description' => 'Cultivo de maíz para grano',
    ]);

    $this->assertDatabaseHas('crops', [
      'name' => 'Maíz',
      'type' => 'grain',
    ]);
    $this->assertNotNull($crop->id);
  }

  /**
   * Test: Verificar relación hasMany con Varieties
   */
  public function test_crop_has_many_varieties(): void
  {
    $crop = Crop::create([
      'name' => 'Tomate',
      'type' => 'vegetable',
      'description' => 'Cultivo de tomate',
    ]);

    $variety1 = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Tomate Cherry',
      'characteristics' => 'Pequeño y dulce',
    ]);

    $variety2 = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Tomate Beefsteak',
      'characteristics' => 'Grande y carnoso',
    ]);

    $this->assertEquals(2, $crop->varieties()->count());
    $this->assertTrue($crop->varieties()->where('id', $variety1->id)->exists());
    $this->assertTrue($crop->varieties()->where('id', $variety2->id)->exists());
  }

  /**
   * Test: Verificar relación hasMany con PhenologicalStages
   */
  public function test_crop_has_many_phenological_stages(): void
  {
    $crop = Crop::create([
      'name' => 'Arroz',
      'type' => 'grain',
      'description' => 'Cultivo de arroz',
    ]);

    $stage1 = PhenologicalStage::create([
      'crop_id' => $crop->id,
      'name' => 'Germinación',
      'order' => 1,
    ]);

    $stage2 = PhenologicalStage::create([
      'crop_id' => $crop->id,
      'name' => 'Floración',
      'order' => 2,
    ]);

    $this->assertEquals(2, $crop->phenologicalStages()->count());
    $this->assertTrue($crop->phenologicalStages()->where('id', $stage1->id)->exists());
  }
}
