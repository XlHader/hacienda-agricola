<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Harvest;
use App\Models\PlantingSeason;
use App\Models\Plot;
use App\Models\Crop;
use App\Models\Variety;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test: Crear una orden con atributos válidos
   */
  public function test_order_creation_with_valid_attributes(): void
  {
    $customer = Customer::create([
      'name' => 'Finca La Esperanza',
      'email' => 'finca@example.com',
      'phone' => '3001234567',
      'address' => 'Calle Principal 123',
    ]);

    $order = Order::create([
      'customer_id' => $customer->id,
      'order_date' => now(),
      'delivery_date' => now()->addDays(7),
      'status' => 'pending',
    ]);

    $this->assertDatabaseHas('orders', [
      'customer_id' => $customer->id,
      'status' => 'pending',
    ]);
    $this->assertNotNull($order->id);
  }

  /**
   * Test: Verificar relación belongsTo con Customer
   */
  public function test_order_belongs_to_customer(): void
  {
    $customer = Customer::create([
      'name' => 'Agrícola Moderna',
      'email' => 'agricola@example.com',
      'phone' => '3009876543',
      'address' => 'Carrera 5 456',
    ]);

    $order = Order::create([
      'customer_id' => $customer->id,
      'order_date' => now(),
      'delivery_date' => now()->addDays(5),
      'status' => 'confirmed',
    ]);

    $this->assertTrue($order->customer()->exists());
    $this->assertEquals($customer->id, $order->customer->id);
    $this->assertEquals('Agrícola Moderna', $order->customer->name);
  }

  /**
   * Test: Verificar relación hasMany con OrderDetails
   */
  public function test_order_has_many_order_details(): void
  {
    $customer = Customer::create([
      'name' => 'Cliente Test',
      'email' => 'cliente@example.com',
      'phone' => '3005555555',
      'address' => 'Dirección Test',
    ]);

    $order = Order::create([
      'customer_id' => $customer->id,
      'order_date' => now(),
      'delivery_date' => now()->addDays(7),
      'status' => 'pending',
    ]);

    // Crear productos y lotes necesarios
    $product = Product::create([
      'name' => 'Producto Test',
      'type' => 'Grano',
      'unit' => 'kg',
    ]);

    $plot = Plot::create([
      'code' => 'PLOT-ORDER-001',
      'area_hectares' => 2.0,
      'soil_type' => 'Franco',
      'soil_analysis' => 'Análisis disponible',
    ]);

    $crop = Crop::create([
      'name' => 'Cultivo Test',
      'type' => 'grain',
      'description' => 'Para test',
    ]);

    $variety = Variety::create([
      'crop_id' => $crop->id,
      'name' => 'Variedad Test',
      'characteristics' => 'Test',
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
      'quantity_kg' => 500,
      'quality' => 'first',
    ]);

    $productBatch1 = ProductBatch::create([
      'product_id' => $product->id,
      'harvest_id' => $harvest->id,
      'batch_number' => 'BATCH-001',
      'quantity' => 250,
      'production_date' => now(),
    ]);

    $productBatch2 = ProductBatch::create([
      'product_id' => $product->id,
      'harvest_id' => $harvest->id,
      'batch_number' => 'BATCH-002',
      'quantity' => 250,
      'production_date' => now(),
    ]);

    $detail1 = OrderDetail::create([
      'order_id' => $order->id,
      'product_batch_id' => $productBatch1->id,
      'quantity' => 100,
      'unit_price' => 5000,
    ]);

    $detail2 = OrderDetail::create([
      'order_id' => $order->id,
      'product_batch_id' => $productBatch2->id,
      'quantity' => 50,
      'unit_price' => 8000,
    ]);

    $this->assertEquals(2, $order->orderDetails()->count());
    $this->assertTrue($order->orderDetails()->where('id', $detail1->id)->exists());
  }
}
