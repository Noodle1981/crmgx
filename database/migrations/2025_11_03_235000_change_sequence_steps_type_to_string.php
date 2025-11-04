<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // For SQLite we recreate the table with a string column for `type`.
            DB::statement('PRAGMA foreign_keys=off;');

            Schema::create('sequence_steps_tmp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sequence_id')->constrained('sequences')->onDelete('cascade');
                $table->string('type')->default('email');
                $table->integer('delay_days')->default(1);
                $table->string('subject')->nullable();
                $table->text('body')->nullable();
                $table->integer('order')->default(0);
                $table->timestamps();
            });

            DB::statement('INSERT INTO sequence_steps_tmp (id, sequence_id, type, delay_days, subject, body, "order", created_at, updated_at) SELECT id, sequence_id, type, delay_days, subject, body, "order", created_at, updated_at FROM sequence_steps;');

            Schema::drop('sequence_steps');
            Schema::rename('sequence_steps_tmp', 'sequence_steps');

            DB::statement('PRAGMA foreign_keys=on;');
        } else {
            // For MySQL / Postgres try a simple ALTER (may require doctrine/dbal in some setups)
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE `sequence_steps` MODIFY `type` VARCHAR(50) NOT NULL DEFAULT 'email'");
            } elseif ($driver === 'pgsql') {
                DB::statement("ALTER TABLE sequence_steps ALTER COLUMN type TYPE VARCHAR(50)");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=off;');

            Schema::create('sequence_steps_old', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sequence_id')->constrained('sequences')->onDelete('cascade');
                $table->enum('type', ['email', 'task'])->default('email');
                $table->integer('delay_days')->default(1);
                $table->string('subject')->nullable();
                $table->text('body')->nullable();
                $table->integer('order')->default(0);
                $table->timestamps();
            });

            DB::statement('INSERT INTO sequence_steps_old (id, sequence_id, type, delay_days, subject, body, "order", created_at, updated_at) SELECT id, sequence_id, type, delay_days, subject, body, "order", created_at, updated_at FROM sequence_steps;');

            Schema::drop('sequence_steps');
            Schema::rename('sequence_steps_old', 'sequence_steps');

            DB::statement('PRAGMA foreign_keys=on;');
        } else {
            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE `sequence_steps` MODIFY `type` ENUM('email','task') NOT NULL DEFAULT 'email'");
            } elseif ($driver === 'pgsql') {
                // Postgres: recreate enum type steps - skipping safe down implementation
            }
        }
    }
};
