<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    // Contexto: Cultivo - Tablas base
    Schema::create('plots', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->decimal('area_hectares', 10, 2);
      $table->string('soil_type');
      $table->text('soil_analysis')->nullable();
      $table->timestamps();
    });

    Schema::create('crops', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->enum('type', ['grain', 'fruit', 'vegetable']);
      $table->text('description')->nullable();
      $table->timestamps();
    });

    Schema::create('varieties', function (Blueprint $table) {
      $table->id();
      $table->foreignId('crop_id')->constrained()->onDelete('cascade');
      $table->string('name');
      $table->text('characteristics')->nullable();
      $table->timestamps();
    });

    Schema::create('planting_seasons', function (Blueprint $table) {
      $table->id();
      $table->foreignId('plot_id')->constrained()->onDelete('cascade');
      $table->foreignId('variety_id')->constrained()->onDelete('cascade');
      $table->date('planting_date');
      $table->date('expected_harvest_date');
      $table->enum('status', ['planned', 'active', 'harvested', 'closed']);
      $table->timestamps();
    });

    Schema::create('water_sources', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->enum('type', ['well', 'river', 'reservoir']);
      $table->decimal('capacity', 12, 2)->nullable();
      $table->timestamps();
    });

    Schema::create('irrigation_systems', function (Blueprint $table) {
      $table->id();
      $table->foreignId('plot_id')->constrained()->onDelete('cascade');
      $table->foreignId('water_source_id')->constrained()->onDelete('cascade');
      $table->enum('type', ['drip', 'sprinkler', 'gravity']);
      $table->timestamps();
    });

    Schema::create('irrigation_schedules', function (Blueprint $table) {
      $table->id();
      $table->foreignId('planting_season_id')->constrained()->onDelete('cascade');
      $table->dateTime('scheduled_date');
      $table->integer('duration_minutes');
      $table->decimal('water_amount', 10, 2);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('irrigation_schedules');
    Schema::dropIfExists('irrigation_systems');
    Schema::dropIfExists('water_sources');
    Schema::dropIfExists('planting_seasons');
    Schema::dropIfExists('varieties');
    Schema::dropIfExists('crops');
    Schema::dropIfExists('plots');
  }
};
