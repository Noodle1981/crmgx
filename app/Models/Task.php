<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'user_id',
        'taskable_type',
        'taskable_id',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];
    
    // ----- RELACIONES -----

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación polimórfica: una tarea puede pertenecer a un Client, Deal, Lead, etc.
    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }
}