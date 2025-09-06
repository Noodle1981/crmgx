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
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;


class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'notes',
        'active',
        'user_id',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'active' => 1, // <-- ¡LA LÍNEA MÁGICA!
    ];

    protected function setPhoneAttribute($value)
{
    if (empty($value)) {
        $this->attributes['phone'] = null;
        return;
    }

    $phoneUtil = PhoneNumberUtil::getInstance();
    try {
        $phoneNumber = $phoneUtil->parse($value, 'AR');
        // Guardamos el número en formato E.164
        $this->attributes['phone'] = $phoneUtil->format($phoneNumber, PhoneNumberFormat::E164);
    } catch (\libphonenumber\NumberParseException $e) {
        // Si la validación falló por alguna razón, guardamos el valor original
        $this->attributes['phone'] = $value;
    }
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
}