<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Activity;
use App\Models\Task;
use App\Models\DealStage;
use App\Models\User;
use App\Models\Client;
use App\Models\SequenceEnrollment;
use App\Models\Concerns\BelongsToUser;

class Deal extends Model
{
    use HasFactory, BelongsToUser;

    protected $fillable = [
        'name',
        'value',
        'client_id',
        'user_id',
        'deal_stage_id',
        'expected_close_date',
        'notes_contact',
        'notes_proposal',
        'notes_negotiation',
        'status',
        'establishment_id',
        'primary_contact_id',
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

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function primaryContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'primary_contact_id');
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class, 'contact_deal');
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

    public function sequenceEnrollments(): MorphMany
    {
        return $this->morphMany(SequenceEnrollment::class, 'enrollable');
    }

}