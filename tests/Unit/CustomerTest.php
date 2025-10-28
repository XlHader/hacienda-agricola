<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Order;
use App\Models\DeliveryPoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test: Crear un cliente con atributos válidos
   */
  public function test_customer_creation_with_valid_attributes(): void
  {
    $customer = Customer::create([
      'name' => 'Distribuidora El Campo',
      'email' => 'distribuidora@example.com',
      'phone' => '3212345678',
      'address' => 'Avenida Principal 789',
    ]);

    $this->assertDatabaseHas('customers', [
      'name' => 'Distribuidora El Campo',
      'email' => 'distribuidora@example.com',
    ]);
    $this->assertNotNull($customer->id);
  }

  /**
   * Test: Verificar relación hasMany con Orders
   */
  public function test_customer_has_many_orders(): void
  {
    $customer = Customer::create([
      'name' => 'Cliente Premium',
      'email' => 'premium@example.com',
      'phone' => '3108888888',
      'address' => 'Zona Premium',
    ]);

    $order1 = Order::create([
      'customer_id' => $customer->id,
      'order_date' => now()->subDays(10),
      'delivery_date' => now()->subDays(3),
      'status' => 'delivered',
    ]);
    $order2 = Order::create([
      'customer_id' => $customer->id,
      'order_date' => now(),
      'delivery_date' => now()->addDays(7),
      'status' => 'pending',
    ]);

    $this->assertEquals(2, $customer->orders()->count());
    $this->assertTrue($customer->orders()->where('id', $order1->id)->exists());
    $this->assertTrue($customer->orders()->where('id', $order2->id)->exists());
  }

  /**
   * Test: Verificar relación hasMany con DeliveryPoints
   */
  public function test_customer_has_many_delivery_points(): void
  {
    $customer = Customer::create([
      'name' => 'Comercio Multi-sede',
      'email' => 'comercio@example.com',
      'phone' => '3107777777',
      'address' => 'Sede Principal',
    ]);

    $point1 = DeliveryPoint::create([
      'customer_id' => $customer->id,
      'name' => 'Bodega Centro',
      'address' => 'Centro de la ciudad',
    ]);

    $point2 = DeliveryPoint::create([
      'customer_id' => $customer->id,
      'name' => 'Bodega Norte',
      'address' => 'Zona norte de la ciudad',
    ]);

    $this->assertEquals(2, $customer->deliveryPoints()->count());
    $this->assertTrue($customer->deliveryPoints()->where('id', $point1->id)->exists());
  }
}
