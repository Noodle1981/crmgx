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
       // En el archivo de la nueva migración
Schema::create('sequence_enrollments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('contact_id')->constrained('contacts')->onDelete('cascade');
    $table->foreignId('sequence_id')->constrained('sequences')->onDelete('cascade');
    $table->foreignId('user_id')->constrained('users'); // Quién lo inscribió
    $table->foreignId('current_step_id')->nullable()->constrained('sequence_steps');
    $table->enum('status', ['active', 'paused', 'completed', 'cancelled'])->default('active');
    $table->timestamp('next_step_due_at')->nullable(); // Fecha y hora para ejecutar el siguiente paso
    $table->text('notes')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sequences_enrollments');
    }
};
