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
        Schema::table('sequence_enrollments', function (Blueprint $table) {
            // Asegurarse de que la columna contact_id existe antes de intentar modificarla
            if (Schema::hasColumn('sequence_enrollments', 'contact_id')) {
                // 1. Eliminar la clave foránea
                // El nombre de la clave foránea puede variar, Laravel lo genera automáticamente.
                // Intentamos adivinar el nombre común, pero si falla, habría que verificarlo.
                $table->dropForeign(['contact_id']);

                // 2. Renombrar la columna
                $table->renameColumn('contact_id', 'enrollable_id');

                // 3. Añadir la columna para el tipo de modelo
                $table->string('enrollable_type')->after('enrollable_id');

                // 4. Añadir un índice para las nuevas columnas polimórficas
                $table->index(['enrollable_id', 'enrollable_type']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sequence_enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('sequence_enrollments', 'enrollable_id')) {
                // Invertir los pasos del método up()
                $table->dropIndex(['enrollable_id', 'enrollable_type']);
                $table->dropColumn('enrollable_type');
                $table->renameColumn('enrollable_id', 'contact_id');
                $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            }
        });
    }
};