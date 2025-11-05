<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Client;
use App\Models\SequenceEnrollment;
use App\Models\Concerns\NormalizesPhone;
use Illuminate\Database\Eloquent\SoftDeletes;



class Contact extends Model
{
    use HasFactory, NormalizesPhone, SoftDeletes;

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
     * Casts por defecto para Contact
     */
    protected $casts = [
        'active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Normaliza email (trim + lowercase)
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value ? strtolower(trim($value)) : null;
    }

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