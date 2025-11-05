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

    /**
     * Get the completed steps for this enrollment.
     */
    public function completedSteps()
    {
        return $this->belongsToMany(SequenceStep::class, 'sequence_step_completions', 'enrollment_id', 'step_id')
                    ->withPivot('completed_at')
                    ->orderBy('completed_at');
    }

    /**
     * Check if a specific step is completed.
     */
    public function isStepCompleted($stepId)
    {
        return $this->completedSteps()->where('sequence_steps.id', $stepId)->exists();
    }

    /**
     * Get the progress percentage of this enrollment.
     */
    public function getProgressPercentage()
    {
        $totalSteps = $this->sequence->steps()->count();
        if ($totalSteps === 0) return 0;
        
        $completedSteps = $this->completedSteps()->count();
        return ($completedSteps / $totalSteps) * 100;
    }
}
