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
}