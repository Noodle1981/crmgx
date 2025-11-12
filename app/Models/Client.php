<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Activity;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Task;
use App\Models\Concerns\NormalizesPhone;
use App\Models\Concerns\BelongsToUser;
use Illuminate\Database\Eloquent\SoftDeletes;


class Client extends Model
{
    use HasFactory, NormalizesPhone, SoftDeletes, BelongsToUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'company',
        'cuit',
        'website',
        'email',
        'phone',
        'fiscal_address_street',
        'fiscal_address_zip_code',
        'fiscal_address_city',
        'fiscal_address_state',
        'fiscal_address_country',
        'economic_activity',
        'art_provider',
        'art_registration_date',
        'hs_manager_name',
        'hs_manager_contact',
        'notes',
        'active',
        'user_id',
        'hs_platform_empresa_id', // <-- Importante para la integración
        'client_status', // <-- Permitir cambio de estado
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'active' => 1, // <-- ¡LA LÍNEA MÁGICA!
    ];

    /**
     * Casts por defecto para este modelo
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
    
    // ----- RELACIONES -----

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function establishments(): HasMany
    {
        return $this->hasMany(Establishment::class);
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'loggable');
    }

    /**
     * Boot the model.
     * Configura eventos para propagar soft-delete y restore a establishments y contacts
     */
    protected static function booted()
    {
        // Propagar soft-delete a establishments y contacts al borrar un cliente
        static::deleting(function ($client) {
            // solo si el borrado es soft-delete (deleted_at será establecido)
            if (method_exists($client, 'establishments')) {
                foreach ($client->establishments as $est) {
                    $est->delete();
                }
            }
            if (method_exists($client, 'contacts')) {
                foreach ($client->contacts as $c) {
                    $c->delete();
                }
            }
        });

        // Al restaurar un cliente restaurar establishments y contacts asociados
        static::restoring(function ($client) {
            foreach ($client->establishments()->withTrashed()->get() as $est) {
                $est->restore();
            }
            foreach ($client->contacts()->withTrashed()->get() as $c) {
                $c->restore();
            }
        });
    }
}