<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    // Contexto: Cultivo - Insumos y Fertilización
    Schema::create('agricultural_inputs', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->enum('type', ['seed', 'fertilizer', 'pesticide', 'herbicide']);
      $table->string('unit');
      $table->timestamps();
    });

    Schema::create('input_batches', function (Blueprint $table) {
      $table->id();
      $table->foreignId('input_id')->constrained('agricultural_inputs')->onDelete('cascade');
      $table->string('batch_number')->unique();
      $table->decimal('quantity', 10, 2);
      $table->date('expiration_date')->nullable();
      $table->decimal('cost', 10, 2);
      $table->timestamps();
    });

    Schema::create('fertilization_plans', function (Blueprint $table) {
      $table->id();
      $table->foreignId('planting_season_id')->constrained()->onDelete('cascade');
      $table->string('name');
      $table->text('description')->nullable();
      $table->timestamps();
    });

    Schema::create('fertilizer_applications', function (Blueprint $table) {
      $table->id();
      $table->foreignId('fertilization_plan_id')->constrained()->onDelete('cascade');
      $table->foreignId('input_batch_id')->constrained()->onDelete('cascade');
      $table->date('application_date');
      $table->decimal('quantity', 10, 2);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('fertilizer_applications');
    Schema::dropIfExists('fertilization_plans');
    Schema::dropIfExists('input_batches');
    Schema::dropIfExists('agricultural_inputs');
  }
};
