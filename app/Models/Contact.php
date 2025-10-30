<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Client;
use App\Models\SequenceEnrollment;



class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'phone',
        'position',
        'notes',
        'active',
    ];

    protected $attributes = [
        'active' => 1,
    ];

    /**
     * Get the client that owns the contact.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the sequence enrollments for the contact.
     */
    public function sequenceEnrollments(): MorphMany
    {
        return $this->morphMany(SequenceEnrollment::class, 'enrollable');
    }
}