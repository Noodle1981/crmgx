<?php

namespace App\Observers;

use App\Models\Establishment;
use Illuminate\Support\Facades\Log;

class EstablishmentObserver
{
    /**
     * Handle the Establishment "created" event.
     */
    public function created(Establishment $establishment): void
    {
        Log::info('Establecimiento creado', [
            'id' => $establishment->id,
            'name' => $establishment->name,
            'client_id' => $establishment->client_id
        ]);
    }

    /**
     * Handle the Establishment "updating" event.
     */
    public function updating(Establishment $establishment): void
    {
        $changes = $establishment->getDirty();
        if (!empty($changes)) {
            Log::info('Actualizando establecimiento', [
                'id' => $establishment->id,
                'changes' => $changes
            ]);
        }
    }

    /**
     * Handle the Establishment "deleted" event.
     */
    public function deleted(Establishment $establishment): void
    {
        Log::info('Establecimiento eliminado', [
            'id' => $establishment->id,
            'name' => $establishment->name,
            'client_id' => $establishment->client_id,
            'is_soft_delete' => $establishment->isForceDeleting() ? 'No' : 'Sí'
        ]);

        // Si es un soft delete, actualizar los contactos asociados
        if (!$establishment->isForceDeleting()) {
            $establishment->contacts()->update(['establishment_id' => null]);
        }
    }

    /**
     * Handle the Establishment "restored" event.
     */
    public function restored(Establishment $establishment): void
    {
        Log::info('Establecimiento restaurado', [
            'id' => $establishment->id,
            'name' => $establishment->name
        ]);
    }

    /**
     * Handle the Establishment "force deleted" event.
     */
    public function forceDeleted(Establishment $establishment): void
    {
        Log::info('Establecimiento eliminado permanentemente', [
            'id' => $establishment->id,
            'name' => $establishment->name
        ]);
    }
}