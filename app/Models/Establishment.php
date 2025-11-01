<?php
// app/Models/Establishment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Establishment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Estos son los campos que se pueden llenar masivamente al crear o actualizar.
     */
    protected $fillable = [
        'client_id',
        'name',
        'address_street',
        'address_city',
        'address_zip_code',
        'address_state',
        'address_country',
        'latitude',
        'longitude',
        'active',
        'notes',
        'hs_platform_sede_id', // <-- Importante para la integración
    ];

    // ----- RELACIONES ELOQUENT -----

    /**
     * Un establecimiento PERTENECE A un cliente.
     * (Relación Inversa de HasMany)
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Un establecimiento PUEDE TENER MUCHOS contactos asignados.
     * (Permite obtener solo los contactos de esta sede)
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}