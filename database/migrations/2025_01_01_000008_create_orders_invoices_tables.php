<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    // Contexto: Venta y Distribución - Pedidos y Facturación
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('customer_id')->constrained()->onDelete('cascade');
      $table->date('order_date');
      $table->date('delivery_date')->nullable();
      $table->enum('status', ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled']);
      $table->timestamps();
    });

    Schema::create('order_details', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->constrained()->onDelete('cascade');
      $table->foreignId('product_batch_id')->constrained()->onDelete('cascade');
      $table->decimal('quantity', 10, 2);
      $table->decimal('unit_price', 10, 2);
      $table->timestamps();
    });

    Schema::create('invoices', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->constrained()->onDelete('cascade');
      $table->string('invoice_number')->unique();
      $table->date('issue_date');
      $table->decimal('total_amount', 10, 2);
      $table->timestamps();
    });

    Schema::create('payments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
      $table->date('payment_date');
      $table->decimal('amount', 10, 2);
      $table->enum('method', ['cash', 'transfer', 'check', 'credit']);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('payments');
    Schema::dropIfExists('invoices');
    Schema::dropIfExists('order_details');
    Schema::dropIfExists('orders');
  }
};
