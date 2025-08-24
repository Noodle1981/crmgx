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
       Schema::create('activities', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users'); // Quién realizó la actividad
    $table->morphs('loggable'); // Esto creará loggable_id y loggable_type
                               // Podrá asociarse a un Client, a un Deal, a un Contact...
    $table->string('type'); // 'call', 'email', 'meeting', 'note'
    $table->text('description'); // "Llamada de seguimiento...", "Reunión para presentar propuesta..."
    $table->json('details')->nullable(); // Guardar metadatos (duración de la llamada, etc.)
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
