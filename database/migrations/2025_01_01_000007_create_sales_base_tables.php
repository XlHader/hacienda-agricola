<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    // Contexto: Venta y Distribución - Base
    Schema::create('customers', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('email')->unique();
      $table->string('phone');
      $table->text('address');
      $table->timestamps();
    });

    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('type');
      $table->string('unit');
      $table->timestamps();
    });

    Schema::create('product_batches', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained()->onDelete('cascade');
      $table->foreignId('harvest_id')->constrained()->onDelete('cascade');
      $table->string('batch_number')->unique();
      $table->decimal('quantity', 10, 2);
      $table->date('production_date');
      $table->timestamps();
    });

    Schema::create('quality_standards', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained()->onDelete('cascade');
      $table->enum('grade', ['extra', 'first', 'second']);
      $table->text('criteria');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('quality_standards');
    Schema::dropIfExists('product_batches');
    Schema::dropIfExists('products');
    Schema::dropIfExists('customers');
  }
};
