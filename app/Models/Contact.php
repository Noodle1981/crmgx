<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Client;
use App\Models\SequenceEnrollment;



class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'establishment_id', // <-- El nuevo campo
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

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function deals(): BelongsToMany
    {
        return $this->belongsToMany(Deal::class);
    }
}