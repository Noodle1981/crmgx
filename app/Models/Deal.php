<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Activity;
use App\Models\Task;
use App\Models\DealStage;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'client_id',
        'user_id',
        'deal_stage_id',
        'expected_close_date',
        'notes',
        'status',
    ];
    
    protected $casts = [
        'expected_close_date' => 'date',
        'value' => 'decimal:2',
    ];
    
    // ----- RELACIONES -----

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dealStage(): BelongsTo
    {
        return $this->belongsTo(DealStage::class);
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

    public function stage(): BelongsTo
{
    return $this->belongsTo(DealStage::class, 'deal_stage_id');
}

}