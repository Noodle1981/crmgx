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
        Schema::table('sequence_steps', function (Blueprint $table) {
            $table->enum('type_new', ['email', 'task', 'call', 'video_call'])->default('email')->after('type');
        });

        // Copy data from old 'type' to new 'type_new'
        DB::table('sequence_steps')->get()->each(function ($step) {
            DB::table('sequence_steps')
                ->where('id', $step->id)
                ->update(['type_new' => $step->type]);
        });

        Schema::table('sequence_steps', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('sequence_steps', function (Blueprint $table) {
            $table->renameColumn('type_new', 'type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sequence_steps', function (Blueprint $table) {
            $table->enum('type_old', ['email', 'task'])->default('email')->after('type');
        });

        DB::table('sequence_steps')->get()->each(function ($step) {
            $oldType = $step->type;
            if (in_array($oldType, ['call', 'video_call'])) {
                $oldType = 'task'; // Revert calls and video calls to tasks
            }
            DB::table('sequence_steps')
                ->where('id', $step->id)
                ->update(['type_old' => $oldType]);
        });

        Schema::table('sequence_steps', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('sequence_steps', function (Blueprint $table) {
            $table->renameColumn('type_old', 'type');
        });
    }
};