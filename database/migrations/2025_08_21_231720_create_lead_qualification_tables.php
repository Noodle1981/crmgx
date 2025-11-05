<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla para los criterios de calificación
        Schema::create('lead_qualification_criteria', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // básico, contacto, negocio, comportamiento
            $table->integer('points');
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });

        // Tabla pivote para la relación entre leads y criterios
        Schema::create('lead_criteria_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_qualification_criteria_id')->constrained()->onDelete('cascade');
            $table->boolean('is_met')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Añadir campos de puntuación a la tabla leads
        Schema::table('leads', function (Blueprint $table) {
            $table->integer('score')->default(0);
            $table->json('qualification_data')->nullable();
            $table->timestamp('last_scored_at')->nullable();
            $table->string('conversion_probability')->nullable();
            $table->json('disqualification_reasons')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_criteria_checks');
        Schema::dropIfExists('lead_qualification_criteria');
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['score', 'qualification_data', 'last_scored_at', 'conversion_probability', 'disqualification_reasons']);
        });
    }
};