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
        Schema::table('deals', function (Blueprint $table) {
            $table->foreignId('establishment_id')->nullable()->constrained('establishments')->onDelete('set null');
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropForeign(['establishment_id']);
            $table->dropForeign(['contact_id']);
            $table->dropColumn(['establishment_id', 'contact_id']);
        });
    }
};
