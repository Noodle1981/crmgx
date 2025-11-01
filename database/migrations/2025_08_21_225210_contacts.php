<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            // --- Vínculos ---
            // El contacto sigue perteneciendo SIEMPRE a un cliente. Si se borra el cliente, se borran sus contactos.
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            
            // NUEVO CAMPO: Opcionalmente, puede pertenecer a un establecimiento específico.
            // Es 'nullable' porque un contacto puede ser corporativo (no ligado a una sede).
            // 'onDelete('set null')' significa que si se borra un establecimiento, el contacto no se borra,
            // simplemente su 'establishment_id' se convierte en NULL, pasando a ser un contacto corporativo.
            $table->foreignId('establishment_id')->nullable()->constrained('establishments')->onDelete('set null');

            // --- Datos del Contacto ---
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('position')->nullable()->comment('Cargo que ocupa, ej: "Jefe de H&S", "Gerente de Planta"');
            $table->text('notes')->nullable();
            
            // Pequeña mejora de sintaxis: boolean es más semántico que tinyInteger para un sí/no.
            $table->boolean('active')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};