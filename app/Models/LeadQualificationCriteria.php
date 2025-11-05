<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LeadQualificationCriteria extends Model
{
    protected $table = 'lead_qualification_criteria';
    protected $fillable = [
        'name',
        'category',
        'points',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'points' => 'integer',
    ];

    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class, 'lead_criteria_checks')
                    ->withPivot('is_met', 'notes')
                    ->withTimestamps();
    }

    // Criterios predefinidos para cada categoría
    public static function getDefaultCriteria(): array
    {
        return [
            // Criterios Básicos
            [
                'name' => 'Información de contacto completa',
                'category' => 'básico',
                'points' => 10,
                'is_required' => true,
            ],
            [
                'name' => 'Email corporativo válido',
                'category' => 'básico',
                'points' => 15,
                'is_required' => false,
            ],
            [
                'name' => 'Teléfono verificado',
                'category' => 'básico',
                'points' => 10,
                'is_required' => true,
            ],
            
            // Criterios de Contacto
            [
                'name' => 'Responde a comunicaciones',
                'category' => 'contacto',
                'points' => 20,
                'is_required' => true,
            ],
            [
                'name' => 'Múltiples interacciones',
                'category' => 'contacto',
                'points' => 15,
                'is_required' => false,
            ],
            
            // Criterios de Negocio
            [
                'name' => 'Presupuesto definido',
                'category' => 'negocio',
                'points' => 25,
                'is_required' => true,
            ],
            [
                'name' => 'Autoridad de decisión',
                'category' => 'negocio',
                'points' => 30,
                'is_required' => true,
            ],
            [
                'name' => 'Necesidad identificada',
                'category' => 'negocio',
                'points' => 25,
                'is_required' => true,
            ],
            
            // Criterios de Comportamiento
            [
                'name' => 'Visita sitio web frecuentemente',
                'category' => 'comportamiento',
                'points' => 10,
                'is_required' => false,
            ],
            [
                'name' => 'Descarga de materiales',
                'category' => 'comportamiento',
                'points' => 15,
                'is_required' => false,
            ],
        ];
    }
}