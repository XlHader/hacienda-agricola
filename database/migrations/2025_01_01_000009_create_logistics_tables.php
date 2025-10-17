<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    // Contexto: Venta y Distribución - Logística
    Schema::create('vehicles', function (Blueprint $table) {
      $table->id();
      $table->string('plate')->unique();
      $table->string('model');
      $table->decimal('capacity_kg', 10, 2);
      $table->enum('status', ['available', 'in_use', 'maintenance']);
      $table->timestamps();
    });

    Schema::create('delivery_points', function (Blueprint $table) {
      $table->id();
      $table->foreignId('customer_id')->constrained()->onDelete('cascade');
      $table->string('name');
      $table->text('address');
      $table->string('coordinates')->nullable();
      $table->timestamps();
    });

    Schema::create('distribution_routes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
      $table->date('route_date');
      $table->enum('status', ['planned', 'in_progress', 'completed']);
      $table->timestamps();
    });

    Schema::create('shipping_guides', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->constrained()->onDelete('cascade');
      $table->foreignId('route_id')->constrained('distribution_routes')->onDelete('cascade');
      $table->foreignId('delivery_point_id')->constrained()->onDelete('cascade');
      $table->string('guide_number')->unique();
      $table->date('shipping_date');
      $table->timestamps();
    });

    Schema::create('product_returns', function (Blueprint $table) {
      $table->id();
      $table->foreignId('customer_id')->constrained()->onDelete('cascade');
      $table->date('return_date');
      $table->text('reason');
      $table->timestamps();
    });

    Schema::create('return_details', function (Blueprint $table) {
      $table->id();
      $table->foreignId('return_id')->constrained('product_returns')->onDelete('cascade');
      $table->foreignId('product_batch_id')->constrained()->onDelete('cascade');
      $table->decimal('quantity', 10, 2);
      $table->timestamps();
    });

    Schema::create('complaints', function (Blueprint $table) {
      $table->id();
      $table->foreignId('customer_id')->constrained()->onDelete('cascade');
      $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
      $table->date('complaint_date');
      $table->text('description');
      $table->enum('status', ['open', 'in_process', 'resolved', 'closed']);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('complaints');
    Schema::dropIfExists('return_details');
    Schema::dropIfExists('product_returns');
    Schema::dropIfExists('shipping_guides');
    Schema::dropIfExists('distribution_routes');
    Schema::dropIfExists('delivery_points');
    Schema::dropIfExists('vehicles');
  }
};
