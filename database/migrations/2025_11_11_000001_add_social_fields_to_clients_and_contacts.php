<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('linkedin')->nullable()->after('website')->comment('Perfil de LinkedIn');
            $table->string('instagram')->nullable()->after('linkedin')->comment('Perfil de Instagram');
            $table->string('facebook')->nullable()->after('instagram')->comment('Perfil de Facebook');
        });
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('linkedin')->nullable()->after('phone')->comment('Perfil de LinkedIn');
            $table->string('instagram')->nullable()->after('linkedin')->comment('Perfil de Instagram');
            $table->string('facebook')->nullable()->after('instagram')->comment('Perfil de Facebook');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['linkedin', 'instagram', 'facebook']);
        });
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['linkedin', 'instagram', 'facebook']);
        });
    }
};
