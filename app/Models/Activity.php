<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loggable_id',
        'loggable_type',
        'type',
        'description',
        'details',
        'deal_stage_id',
    ];

    /**
     * Colores asociados a cada tipo de actividad
     */
    public static $typeColors = [
        'deal_created' => 'border-green-500',
        'deal_updated' => 'border-blue-500',
        'deal_won' => 'border-green-600',
        'deal_lost' => 'border-red-500',
        'lead_created' => 'border-yellow-500',
        'lead_updated' => 'border-blue-500',
        'lead_converted' => 'border-green-500',
        'client_created' => 'border-purple-500',
        'client_updated' => 'border-blue-500',
        'task_created' => 'border-indigo-500',
        'task_completed' => 'border-green-500',
        'login' => 'border-gray-500',
        'logout' => 'border-gray-400',
        'error' => 'border-red-600',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    // ----- RELACIONES -----

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación polimórfica: una actividad puede pertenecer a un Client, Deal, Contact, etc.
    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(DealStage::class, 'deal_stage_id');
    }

    /**
     * Obtiene el color de borde asociado al tipo de actividad
     */
    public function getBorderColorAttribute(): string
    {
        return static::$typeColors[$this->type] ?? 'border-gray-300';
    }

    /**
     * Registra una nueva actividad en el sistema
     */
    public static function log($userId, $type, $description, $loggable = null, $details = null): self
    {
        return static::create([
            'user_id' => $userId,
            'type' => $type,
            'description' => $description,
            'loggable_id' => $loggable ? $loggable->id : null,
            'loggable_type' => $loggable ? get_class($loggable) : null,
            'details' => $details,
        ]);
    }
}