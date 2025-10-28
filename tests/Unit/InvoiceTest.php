<?php

namespace Tests\Unit;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test: Crear una factura con atributos válidos
   */
  public function test_invoice_creation_with_valid_attributes(): void
  {
    $customer = Customer::create([
      'name' => 'Comercio Local',
      'email' => 'comercio@example.com',
      'phone' => '3102222222',
      'address' => 'Centro Comercial',
    ]);

    $order = Order::create([
      'customer_id' => $customer->id,
      'order_date' => now(),
      'delivery_date' => now()->addDays(7),
      'status' => 'delivered',
    ]);
    $invoice = Invoice::create([
      'order_id' => $order->id,
      'invoice_number' => 'INV-2024-001',
      'issue_date' => now(),
      'total_amount' => 500000,
    ]);

    $this->assertDatabaseHas('invoices', [
      'order_id' => $order->id,
      'invoice_number' => 'INV-2024-001',
      'total_amount' => 500000,
    ]);
    $this->assertNotNull($invoice->id);
  }

  /**
   * Test: Verificar relación belongsTo con Order
   */
  public function test_invoice_belongs_to_order(): void
  {
    $customer = Customer::create([
      'name' => 'Distribuidora Mayorista',
      'email' => 'mayorista@example.com',
      'phone' => '3103333333',
      'address' => 'Zona Industrial',
    ]);

    $order = Order::create([
      'customer_id' => $customer->id,
      'order_date' => now()->subDays(5),
      'delivery_date' => now(),
      'status' => 'delivered',
    ]);
    $invoice = Invoice::create([
      'order_id' => $order->id,
      'invoice_number' => 'INV-2024-002',
      'issue_date' => now(),
      'total_amount' => 750000,
    ]);

    $this->assertTrue($invoice->order()->exists());
    $this->assertEquals($order->id, $invoice->order->id);
  }

  /**
   * Test: Verificar relación hasMany con Payments
   */
  public function test_invoice_has_many_payments(): void
  {
    $customer = Customer::create([
      'name' => 'Empresa Agrícola Premium',
      'email' => 'premium@example.com',
      'phone' => '3104444444',
      'address' => 'Sector Rural',
    ]);

    $order = Order::create([
      'customer_id' => $customer->id,
      'order_date' => now()->subDays(10),
      'delivery_date' => now()->subDays(3),
      'status' => 'delivered',
    ]);
    $invoice = Invoice::create([
      'order_id' => $order->id,
      'invoice_number' => 'INV-2024-003',
      'issue_date' => now()->subDays(5),
      'total_amount' => 1000000,
    ]);

    $payment1 = Payment::create([
      'invoice_id' => $invoice->id,
      'payment_date' => now()->subDays(4),
      'amount' => 500000,
      'method' => 'transfer',
    ]);

    $payment2 = Payment::create([
      'invoice_id' => $invoice->id,
      'payment_date' => now()->subDays(2),
      'amount' => 500000,
      'method' => 'check',
    ]);

    $this->assertEquals(2, $invoice->payments()->count());
    $this->assertTrue($invoice->payments()->where('id', $payment1->id)->exists());
    $this->assertTrue($invoice->payments()->where('id', $payment2->id)->exists());
  }
}
