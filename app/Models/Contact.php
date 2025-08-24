<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- ¡EL 'USE' CORRECTO!

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
    public function sequenceEnrollments(): HasMany // <-- ¡EL 'TYPE HINT' CORRECTO!
    {
        return $this->hasMany(SequenceEnrollment::class);
    }
}