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
    Schema::create('deals', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->decimal('value', 15, 2)->nullable();
        $table->foreignId('client_id')->constrained('clients')->onDelete('cascade'); 
        $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
        $table->foreignId('deal_stage_id')->constrained('deal_stages')->onDelete('restrict');
        $table->date('expected_close_date')->nullable();
        $table->enum('status', ['open', 'won', 'lost'])->default('open');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('deals');
    }
};
