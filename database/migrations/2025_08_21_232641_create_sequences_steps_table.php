<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sequence_steps', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sequence_id')->constrained('sequences')->onDelete('cascade');
    $table->enum('type', ['email', 'task'])->default('email'); // Tipo de paso
    $table->integer('delay_days')->default(1); // Días a esperar después del paso anterior
    $table->string('subject')->nullable(); // Asunto del email
    $table->text('body')->nullable(); // Cuerpo del email o descripción de la tarea
    $table->integer('order')->default(0);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sequences_steps');
    }
};
