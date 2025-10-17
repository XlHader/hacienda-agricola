<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    // Contexto: Cultivo - Monitoreo Fenológico y Labores
    Schema::create('phenological_stages', function (Blueprint $table) {
      $table->id();
      $table->foreignId('crop_id')->constrained()->onDelete('cascade');
      $table->string('name');
      $table->integer('order');
      $table->timestamps();
    });

    Schema::create('phenological_observations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('planting_season_id')->constrained()->onDelete('cascade');
      $table->foreignId('stage_id')->constrained('phenological_stages')->onDelete('cascade');
      $table->date('observation_date');
      $table->text('notes')->nullable();
      $table->timestamps();
    });

    Schema::create('agricultural_tasks', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->timestamps();
    });

    Schema::create('task_assignments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('task_id')->constrained('agricultural_tasks')->onDelete('cascade');
      $table->foreignId('planting_season_id')->constrained()->onDelete('cascade');
      $table->foreignId('employee_id')->constrained()->onDelete('cascade');
      $table->date('assigned_date');
      $table->date('completion_date')->nullable();
      $table->timestamps();
    });

    Schema::create('harvests', function (Blueprint $table) {
      $table->id();
      $table->foreignId('planting_season_id')->constrained()->onDelete('cascade');
      $table->date('harvest_date');
      $table->decimal('quantity_kg', 10, 2);
      $table->enum('quality', ['extra', 'first', 'second']);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('harvests');
    Schema::dropIfExists('task_assignments');
    Schema::dropIfExists('agricultural_tasks');
    Schema::dropIfExists('phenological_observations');
    Schema::dropIfExists('phenological_stages');
  }
};
