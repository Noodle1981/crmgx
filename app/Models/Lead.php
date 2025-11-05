<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Concerns\NormalizesPhone;
use App\Models\Concerns\BelongsToUser;

class Lead extends Model
{
    use HasFactory, NormalizesPhone, BelongsToUser;

    /**
     * Normaliza email (trim + lowercase)
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value ? strtolower(trim($value)) : null;
    }

    //--- CAMPOS DEL LEAD ---
    // Estos campos definen toda la información que puede almacenarse para un lead
    // Incluye datos básicos, métricas de calificación, y campos de enriquecimiento
protected $fillable = [
        'name',      // Nombre del contacto
        'company',   // Nombre de la empresa
        'email',     // Correo electrónico principal
        'phone',
        'source',
        'status',
        'notes',
        'user_id',
        'score',
        'qualification_data',
        'last_scored_at',
        'conversion_probability',
        'disqualification_reasons',
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
        'last_enrichment_at',
    ];

    protected $casts = [
        'qualification_data' => 'array',
        'disqualification_reasons' => 'array',
        'last_scored_at' => 'datetime',
        'process_steps' => 'array',
        'stage_history' => 'array',
        'social_profiles' => 'array',
        'custom_fields' => 'array',
        'last_interaction_at' => 'datetime',
        'lost_at' => 'datetime',
        'last_enrichment_at' => 'datetime',
        'email_verified' => 'boolean',
        'phone_verified' => 'boolean',
    ];

    // ----- RELACIONES -----

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // Relación polimórfica para tareas
    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    // Relación polimórfica para actividades
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'loggable');
    }

    // Relación con los recordatorios
    public function reminders()
    {
        return $this->hasMany(LeadReminder::class);
    }

    //--- SISTEMA DE RECORDATORIOS ---
    // Permite crear recordatorios rápidos para seguimiento
    // - Título y descripción de la tarea
    // - Fecha de vencimiento (por defecto mañana)
    // - Prioridad del recordatorio
    // Método para crear un recordatorio rápido
    public function createReminder(string $title, string $description = null, $dueDate = null, $priority = 'medium')
    {
        return $this->reminders()->create([
            'user_id' => $this->user_id,
            'title' => $title,
            'description' => $description,
            'due_date' => $dueDate ?? now()->addDays(1),
            'priority' => $priority,
        ]);
    }

    // Relación con los criterios de calificación
    public function qualificationCriteria()
    {
        return $this->belongsToMany(LeadQualificationCriteria::class, 'lead_criteria_checks')
                    ->withPivot('is_met', 'notes')
                    ->withTimestamps();
    }

    //--- SISTEMA DE PUNTUACIÓN ---
    // Este método calcula la puntuación del lead basándose en:
    // 1. Criterios de calificación cumplidos
    // 2. Información completa (email, teléfono, empresa)
    // 3. Nivel de interacción (actividades)
    // La puntuación se usa para determinar la probabilidad de conversión
    public function calculateScore()
    {
        $score = 0;
        $metCriteria = [];
        $unmetRequired = [];

        foreach ($this->qualificationCriteria as $criteria) {
            if ($criteria->pivot->is_met) {
                $score += $criteria->points;
                $metCriteria[] = $criteria->name;
            } elseif ($criteria->is_required) {
                $unmetRequired[] = $criteria->name;
            }
        }

        // Puntos adicionales por comportamiento
        if ($this->email) $score += 5;
        if ($this->phone) $score += 5;
        if ($this->company) $score += 10;
        if ($this->activities()->count() > 2) $score += 15;

        // Calcular probabilidad de conversión
        $probability = $this->calculateConversionProbability($score);

        $this->update([
            'score' => $score,
            'last_scored_at' => now(),
            'conversion_probability' => $probability,
            'qualification_data' => [
                'met_criteria' => $metCriteria,
                'unmet_required' => $unmetRequired,
                'total_criteria' => $this->qualificationCriteria->count(),
                'met_count' => count($metCriteria)
            ]
        ]);

        return $score;
    }

    // Calcular la probabilidad de conversión
    private function calculateConversionProbability($score)
    {
        if ($score < 30) return 'baja';
        if ($score < 60) return 'media';
        if ($score < 90) return 'alta';
        return 'muy alta';
    }

    // Verificar si el lead cumple con los criterios mínimos
    public function isQualified()
    {
        // Verificar criterios requeridos
        $unmetRequired = $this->qualificationCriteria()
                             ->where('is_required', true)
                             ->wherePivot('is_met', false)
                             ->count();

        if ($unmetRequired > 0) return false;

        // Verificar puntuación mínima (60 puntos)
        if ($this->score < 60) return false;

        return true;
    }

    // Actualizar el estado basado en la calificación
    public function updateStatusBasedOnQualification()
    {
        if ($this->isQualified()) {
            $this->update(['status' => 'calificado']);
            
            // Notificar al usuario asignado
            $this->user->notify(new LeadQualifiedNotification($this));
            
            return true;
        }
        
        return false;
    }

    // Métodos para el flujo del proceso
    public function recordInteraction($type, $details = null)
    {
        $this->last_interaction_at = now();
        $this->stage_history = $this->stage_history ?? [];
        
        $this->stage_history[] = [
            'status' => $this->status,
            'timestamp' => now()->toDateTimeString(),
            'type' => $type,
            'details' => $details,
        ];

        $this->save();
    }

    public function markAsLost(LossReason $reason, string $notes = null)
    {
        $this->update([
            'status' => 'perdido',
            'loss_reason_id' => $reason->id,
            'loss_notes' => $notes,
            'lost_at' => now(),
        ]);

        $this->recordInteraction('lost', [
            'reason' => $reason->name,
            'notes' => $notes,
        ]);

        // Notificar al usuario
        $this->user->notify(new LeadLostNotification($this));
    }

    //--- PROCESO DE CONVERSIÓN ---
    // Este método maneja la conversión de un lead a cliente
    // Realiza las siguientes acciones:
    // 1. Crea un nuevo cliente con la información del lead
    // 2. Crea un contacto principal
    // 3. Crea una oportunidad (deal) inicial
    // 4. Registra el seguimiento de la conversión
    // Todo se ejecuta en una transacción para mantener la integridad de los datos
    public function convertToClient(): LeadConversionFollowUp
    {
        DB::beginTransaction();
        try {
            // Crear el cliente
            $client = Client::create([
                'name' => $this->company ?? $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'user_id' => $this->user_id,
                'industry' => $this->industry,
                'company_size' => $this->company_size,
                'annual_revenue' => $this->annual_revenue,
                'website' => $this->website,
            ]);

            // Crear el contacto principal
            $contact = $client->contacts()->create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'job_title' => $this->job_title,
                'department' => $this->department,
            ]);

            // Crear el deal
            $deal = $client->deals()->create([
                'name' => 'Oportunidad - ' . $client->name,
                'user_id' => $this->user_id,
                'deal_stage_id' => 1, // Primera etapa
                'status' => 'open',
            ]);

            // Registrar la conversión
            $followUp = LeadConversionFollowUp::create([
                'lead_id' => $this->id,
                'client_id' => $client->id,
                'deal_id' => $deal->id,
                'status' => 'converted',
                'converted_at' => now(),
                'conversion_data' => [
                    'original_score' => $this->score,
                    'days_to_convert' => $this->days_in_pipeline,
                    'qualification_data' => $this->qualification_data,
                    'source' => $this->source,
                ],
            ]);

            $this->update([
                'status' => 'convertido',
                'process_steps' => array_merge($this->process_steps ?? [], [
                    'converted_at' => now()->toDateTimeString(),
                    'converted_to' => [
                        'client_id' => $client->id,
                        'contact_id' => $contact->id,
                        'deal_id' => $deal->id,
                    ],
                ]),
            ]);

            DB::commit();
            return $followUp;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    //--- ENRIQUECIMIENTO DE DATOS ---
    // Sistema de enriquecimiento que:
    // - Valida y verifica email y teléfono
    // - Busca información adicional de la empresa
    // - Actualiza campos como industry, company_size, website
    // - Marca timestamps de verificación
    // Métodos para enriquecimiento de datos
    public function enrichData()
    {
        $enrichmentService = new \App\Services\LeadEnrichmentService();
        return $enrichmentService->basicEnrichment($this);
    }

    // Relaciones adicionales
    public function lossReason()
    {
        return $this->belongsTo(LossReason::class);
    }

    public function conversionFollowUp()
    {
        return $this->hasOne(LeadConversionFollowUp::class);
    }

    // Métodos de utilidad
    public function getDaysInPipeline(): int
    {
        return $this->created_at->diffInDays(now());
    }

    public function updateDaysInPipeline()
    {
        $this->days_in_pipeline = $this->getDaysInPipeline();
        $this->save();
    }
}