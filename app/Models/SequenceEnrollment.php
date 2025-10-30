<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SequenceEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollable_id',
        'enrollable_type',
        'sequence_id',
        'user_id',
        'current_step_id',
        'status',
        'next_step_due_at',
        'notes',
    ];

    /**
     * Get the parent enrollable model (contact, deal, etc.).
     */
    public function enrollable()
    {
        return $this->morphTo();
    }

    /**
     * Get the sequence template for this enrollment.
     */
    public function sequence()
    {
        return $this->belongsTo(Sequence::class);
    }

    /**
     * Get the user who enrolled this.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the current step for this enrollment.
     */
    public function currentStep()
    {
        return $this->belongsTo(SequenceStep::class, 'current_step_id');
    }
}
