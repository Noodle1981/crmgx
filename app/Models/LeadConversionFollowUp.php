<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadConversionFollowUp extends Model
{
    protected $fillable = [
        'lead_id',
        'client_id',
        'deal_id',
        'status',
        'conversion_data',
        'converted_at',
    ];

    protected $casts = [
        'conversion_data' => 'array',
        'converted_at' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }
}