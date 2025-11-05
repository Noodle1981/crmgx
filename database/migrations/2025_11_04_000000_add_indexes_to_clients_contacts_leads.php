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
        // Índices para clients
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'email')) return;
            $table->index('email');
            if (Schema::hasColumn('clients', 'phone')) {
                $table->index('phone');
            }
            if (Schema::hasColumn('clients', 'user_id')) {
                $table->index('user_id');
            }
            if (Schema::hasColumn('clients', 'active')) {
                $table->index('active');
            }
        });

        // Índices para contacts
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'email')) return;
            $table->index('email');
            if (Schema::hasColumn('contacts', 'phone')) {
                $table->index('phone');
            }
            if (Schema::hasColumn('contacts', 'client_id')) {
                $table->index('client_id');
            }
        });

        // Índices para leads
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'email')) return;
            $table->index('email');
            if (Schema::hasColumn('leads', 'phone')) {
                $table->index('phone');
            }
            if (Schema::hasColumn('leads', 'user_id')) {
                $table->index('user_id');
            }
            if (Schema::hasColumn('leads', 'status')) {
                $table->index('status');
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
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'email')) {
                $table->dropIndex(['email']);
            }
            if (Schema::hasColumn('clients', 'phone')) {
                $table->dropIndex(['phone']);
            }
            if (Schema::hasColumn('clients', 'user_id')) {
                $table->dropIndex(['user_id']);
            }
            if (Schema::hasColumn('clients', 'active')) {
                $table->dropIndex(['active']);
            }
        });

        Schema::table('contacts', function (Blueprint $table) {
            if (Schema::hasColumn('contacts', 'email')) {
                $table->dropIndex(['email']);
            }
            if (Schema::hasColumn('contacts', 'phone')) {
                $table->dropIndex(['phone']);
            }
            if (Schema::hasColumn('contacts', 'client_id')) {
                $table->dropIndex(['client_id']);
            }
        });

        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'email')) {
                $table->dropIndex(['email']);
            }
            if (Schema::hasColumn('leads', 'phone')) {
                $table->dropIndex(['phone']);
            }
            if (Schema::hasColumn('leads', 'user_id')) {
                $table->dropIndex(['user_id']);
            }
            if (Schema::hasColumn('leads', 'status')) {
                $table->dropIndex(['status']);
            }
        });
    }
};
