<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    // Contexto: Cultivo - Control Fitosanitario
    Schema::create('pest_diseases', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->enum('type', ['pest', 'disease']);
      $table->text('description')->nullable();
      $table->timestamps();
    });

    Schema::create('phytosanitary_incidents', function (Blueprint $table) {
      $table->id();
      $table->foreignId('planting_season_id')->constrained()->onDelete('cascade');
      $table->foreignId('pest_disease_id')->constrained()->onDelete('cascade');
      $table->date('detection_date');
      $table->enum('severity', ['low', 'medium', 'high']);
      $table->timestamps();
    });

    Schema::create('phytosanitary_treatments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('incident_id')->constrained('phytosanitary_incidents')->onDelete('cascade');
      $table->foreignId('input_batch_id')->constrained()->onDelete('cascade');
      $table->date('application_date');
      $table->decimal('quantity', 10, 2);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('phytosanitary_treatments');
    Schema::dropIfExists('phytosanitary_incidents');
    Schema::dropIfExists('pest_diseases');
  }
};
