<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla para razones de pérdida predefinidas
        Schema::create('loss_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Tabla para el seguimiento post-conversión
        Schema::create('lead_conversion_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('deal_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->json('conversion_data');
            $table->timestamp('converted_at');
            $table->timestamps();
        });

        // Añadir campos para enriquecimiento de datos y flujo del proceso
        Schema::table('leads', function (Blueprint $table) {
            // Campos para el flujo del proceso
            $table->json('process_steps')->nullable();
            $table->timestamp('last_interaction_at')->nullable();
            $table->integer('days_in_pipeline')->default(0);
            $table->json('stage_history')->nullable();
            
            // Campos para razones de pérdida
            $table->foreignId('loss_reason_id')->nullable()->constrained();
            $table->text('loss_notes')->nullable();
            $table->timestamp('lost_at')->nullable();
            
            // Campos enriquecidos
            $table->string('industry')->nullable();
            $table->string('company_size')->nullable();
            $table->string('annual_revenue')->nullable();
            $table->string('website')->nullable();
            $table->json('social_profiles')->nullable();
            $table->string('job_title')->nullable();
            $table->string('department')->nullable();
            $table->json('custom_fields')->nullable();
            
            // Campos de verificación
            $table->boolean('email_verified')->default(false);
            $table->boolean('phone_verified')->default(false);
            $table->timestamp('last_enrichment_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'process_steps',
                'last_interaction_at',
                'days_in_pipeline',
                'stage_history',
                'loss_reason_id',
                'loss_notes',
                'lost_at',
                'industry',
                'company_size',
                'annual_revenue',
                'website',
                'social_profiles',
                'job_title',
                'department',
                'custom_fields',
                'email_verified',
                'phone_verified',
                'last_enrichment_at'
            ]);
        });
        
        Schema::dropIfExists('lead_conversion_follow_ups');
        Schema::dropIfExists('loss_reasons');
    }
};