<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('establishments', function (Blueprint $table) {
            if (!Schema::hasColumn('establishments', 'deleted_at')) {
                $table->softDeletes();
            }
            if (Schema::hasColumn('establishments', 'client_id')) {
                $table->index('client_id');
            }
            if (Schema::hasColumn('establishments', 'active')) {
                $table->index('active');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('establishments', function (Blueprint $table) {
            if (Schema::hasColumn('establishments', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }
            if (Schema::hasColumn('establishments', 'client_id')) {
                $table->dropIndex(['client_id']);
            }
            if (Schema::hasColumn('establishments', 'active')) {
                $table->dropIndex(['active']);
            }
        });
    }
};