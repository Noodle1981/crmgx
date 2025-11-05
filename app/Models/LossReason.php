<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LossReason extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
    ];

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    // Razones predefinidas
    public static function getDefaultReasons(): array
    {
        return [
            // Razones de Presupuesto
            [
                'name' => 'Presupuesto insuficiente',
                'category' => 'presupuesto',
                'description' => 'El presupuesto del lead está por debajo del mínimo requerido',
            ],
            [
                'name' => 'Precio muy alto',
                'category' => 'presupuesto',
                'description' => 'El lead considera que nuestros precios están fuera de su alcance',
            ],
            
            // Razones de Competencia
            [
                'name' => 'Eligió competidor',
                'category' => 'competencia',
                'description' => 'El lead optó por una solución de la competencia',
            ],
            [
                'name' => 'Mejor oferta competidor',
                'category' => 'competencia',
                'description' => 'La competencia ofreció mejores condiciones o precios',
            ],
            
            // Razones de Timing
            [
                'name' => 'No es el momento',
                'category' => 'timing',
                'description' => 'El lead no está listo para implementar la solución',
            ],
            [
                'name' => 'Proyecto cancelado',
                'category' => 'timing',
                'description' => 'El proyecto o iniciativa fue cancelada o pospuesta',
            ],
            
            // Razones de Producto
            [
                'name' => 'Falta de características',
                'category' => 'producto',
                'description' => 'El producto no cumple con todas las necesidades del lead',
            ],
            [
                'name' => 'Complejidad técnica',
                'category' => 'producto',
                'description' => 'La solución es demasiado compleja para el lead',
            ],
            
            // Razones de Comunicación
            [
                'name' => 'Sin respuesta',
                'category' => 'comunicación',
                'description' => 'El lead dejó de responder a las comunicaciones',
            ],
            [
                'name' => 'Datos incorrectos',
                'category' => 'comunicación',
                'description' => 'La información de contacto no es correcta o está desactualizada',
            ],
        ];
    }
}