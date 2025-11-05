<?php

namespace App\Observers;

use App\Models\Contact;
use Illuminate\Support\Facades\Log;

class ContactObserver
{
    /**
     * Handle the Contact "created" event.
     */
    public function created(Contact $contact): void
    {
        Log::info('Contacto creado', [
            'id' => $contact->id,
            'name' => $contact->name,
            'client_id' => $contact->client_id
        ]);
    }

    /**
     * Handle the Contact "updating" event.
     */
    public function updating(Contact $contact): void
    {
        $changes = $contact->getDirty();
        if (!empty($changes)) {
            Log::info('Actualizando contacto', [
                'id' => $contact->id,
                'changes' => $changes
            ]);
        }
    }

    /**
     * Handle the Contact "deleted" event.
     */
    public function deleted(Contact $contact): void
    {
        Log::info('Contacto eliminado', [
            'id' => $contact->id,
            'name' => $contact->name,
            'client_id' => $contact->client_id,
            'is_soft_delete' => $contact->isForceDeleting() ? 'No' : 'Sí'
        ]);
    }

    /**
     * Handle the Contact "restored" event.
     */
    public function restored(Contact $contact): void
    {
        Log::info('Contacto restaurado', [
            'id' => $contact->id,
            'name' => $contact->name
        ]);
    }

    /**
     * Handle the Contact "force deleted" event.
     */
    public function forceDeleted(Contact $contact): void
    {
        Log::info('Contacto eliminado permanentemente', [
            'id' => $contact->id,
            'name' => $contact->name
        ]);
    }
}