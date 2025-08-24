<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SequenceEnrollment;
use App\Models\Client; // <-- Añadido para el taskable_type
use Carbon\Carbon;

class ProcessSequences extends Command
{
    /**
     * El nombre y la firma del comando de la consola.
     * Este es el nombre que usaremos para llamarlo: php artisan app:process-sequences
     * @var string
     */
    protected $signature = 'app:process-sequences';

    /**
     * La descripción del comando, aparecerá en la lista de comandos.
     * @var string
     */
    protected $description = 'Procesa las inscripciones de secuencias activas y ejecuta los pasos correspondientes.';

    /**
     * El método principal que se ejecuta cuando llamamos al comando.
     */
public function handle()
{
    $this->info('🤖 Iniciando depuración de secuencias...');

    // 1. PRIMERO, VAMOS A VER QUÉ HAY EN LA BASE DE DATOS
    $allActiveEnrollments = SequenceEnrollment::where('status', 'active')
        ->with('currentStep') // Cargamos el paso actual
        ->get();

    if ($allActiveEnrollments->isEmpty()) {
        $this->warn('No se encontraron inscripciones con estado "active" en la base de datos.');
        return self::SUCCESS;
    }

    $this->info("Se encontraron {$allActiveEnrollments->count()} inscripciones activas en total. Detalles:");
    foreach ($allActiveEnrollments as $enrollment) {
        $this->line(" - Inscripción ID: {$enrollment->id}, Próximo paso: {$enrollment->next_step_due_at}, Delay del paso: {$enrollment->currentStep->delay_days} días");
    }

    $this->line('---'); // Separador

    // 2. AHORA, EJECUTAMOS LA CONSULTA REAL
    $this->info('Ejecutando la consulta de procesamiento para hoy...');
    $enrollmentsToProcess = SequenceEnrollment::where('status', 'active')
        ->whereDate('next_step_due_at', '<=', Carbon::today())
        ->get();
        
    if ($enrollmentsToProcess->isEmpty()) {
        $this->info('✅ La consulta no devolvió resultados para hoy.');
        $this->info('Fecha actual del servidor (Carbon::today()): ' . Carbon::today()->toDateString());
        return self::SUCCESS;
    }

    // Si llega hasta aquí, significa que SÍ encontró algo que procesar.
    $this->info("✅ ¡ÉXITO! Se encontraron {$enrollmentsToProcess->count()} inscripciones para procesar hoy.");
    // (Aquí iría el resto de la lógica que ya teníamos, pero por ahora esto es suficiente)

    return self::SUCCESS;
}
}