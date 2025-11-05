<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sequence_step_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('sequence_enrollments')->onDelete('cascade');
            $table->foreignId('step_id')->constrained('sequence_steps')->onDelete('cascade');
            $table->timestamp('completed_at');
            $table->timestamps();

            // Un paso solo puede ser completado una vez por inscripción
            $table->unique(['enrollment_id', 'step_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sequence_step_completions');
    }
};