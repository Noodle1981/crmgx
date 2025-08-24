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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("description")->nullable();
            $table->enum('status', ['pendiente', 'completado', 'en_proceso'])->default('pendiente');
            $table->date('due_date');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            
            // Aquí está la magia: la hacemos polimórfica desde el inicio
            $table->morphs('taskable'); // Crea taskable_id y taskable_type

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};