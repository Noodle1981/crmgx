<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');

            // --- Campos Clave de Sincronización con Plataforma H&S ---
            $table->string('name')->comment('Nombre del establecimiento. SINCRONIZADO con "nombre_sede" en H&S.');
            $table->string('address_street')->nullable()->comment('Dirección. SINCRONIZADO con "direccion" en H&S.');
            $table->string('address_city')->nullable()->comment('Localidad. SINCRONIZADO con "localidad" en H&S.');
            
            // --- ID Externo para Vínculo Permanente ---
            $table->unsignedBigInteger('hs_platform_sede_id')->nullable()->unique()->comment('ID de la tabla "sedes" en la plataforma H&S. Clave para la integración.');
            
            // --- Campos Exclusivos del CRM (Información detallada del establecimiento) ---
            $table->string('address_zip_code')->nullable()->comment('Código Postal. (Dato de CRM)');
            $table->string('address_state')->nullable()->comment('Provincia. (Dato de CRM)');
            $table->string('address_country')->nullable()->comment('País. (Dato de CRM)');
            
            // Coordenadas Geográficas (Este es un buen candidato para sincronizar si H&S lo necesita)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Campos de gestión del establecimiento en el CRM
            $table->boolean('active')->default(true);
            $table->text('notes')->nullable()->comment('Notas específicas de este establecimiento. (Exclusivo del CRM)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('establishments');
    }
};