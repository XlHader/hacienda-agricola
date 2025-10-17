<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    // Contexto: Gestión de Recursos
    Schema::create('skills', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->timestamps();
    });

    Schema::create('employee_skills', function (Blueprint $table) {
      $table->id();
      $table->foreignId('employee_id')->constrained()->onDelete('cascade');
      $table->foreignId('skill_id')->constrained()->onDelete('cascade');
      $table->date('certification_date');
      $table->timestamps();
    });

    Schema::create('trainings', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description')->nullable();
      $table->date('start_date');
      $table->date('end_date');
      $table->timestamps();
    });

    Schema::create('training_participations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('training_id')->constrained()->onDelete('cascade');
      $table->foreignId('employee_id')->constrained()->onDelete('cascade');
      $table->enum('completion_status', ['pending', 'completed', 'failed']);
      $table->timestamps();
    });

    Schema::create('machinery', function (Blueprint $table) {
      $table->id();
      $table->string('code')->unique();
      $table->string('name');
      $table->enum('type', ['tractor', 'harvester', 'sprayer', 'implement']);
      $table->string('model');
      $table->date('acquisition_date');
      $table->timestamps();
    });

    Schema::create('maintenance_records', function (Blueprint $table) {
      $table->id();
      $table->foreignId('machinery_id')->constrained()->onDelete('cascade');
      $table->date('maintenance_date');
      $table->enum('type', ['preventive', 'corrective']);
      $table->text('description');
      $table->decimal('cost', 10, 2);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('maintenance_records');
    Schema::dropIfExists('machinery');
    Schema::dropIfExists('training_participations');
    Schema::dropIfExists('trainings');
    Schema::dropIfExists('employee_skills');
    Schema::dropIfExists('skills');
  }
};
