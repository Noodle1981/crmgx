<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Store a newly created activity for a client.
     */
    public function storeForClient(Request $request, Client $client)
    {
        // 1. Seguridad
        if (Auth::user()->id !== $client->user_id) {
            abort(403);
        }

        // 2. Validación
        $validated = $request->validate([
            'type' => 'required|string|in:note,call,meeting,email',
            'description' => 'required|string|max:5000',
        ]);

        // 3. Creación de la actividad (usando la magia del polimorfismo)
        $client->activities()->create([
            'type' => $validated['type'],
            'description' => $validated['description'],
            'user_id' => Auth::id(), // Asignamos el usuario que la registra
        ]);

        // 4. Redirección de vuelta a la ficha del cliente
        return redirect()->route('clients.show', $client)->with('success', '¡Actividad registrada con éxito!');
    }

    /**
     * Store a newly created activity for a deal.
     */
    public function storeForDeal(Request $request, Deal $deal)
    {
        // 1. Seguridad
        if (Auth::user()->id !== $deal->user_id) {
            abort(403);
        }

        // 2. Validación
        $validated = $request->validate([
            'type' => 'required|string|in:note,call,meeting,email',
            'description' => 'required|string|max:5000',
            'status' => 'required|string|in:pendiente,en espera,completada',
        ]);

        // 3. Creación de la actividad
        $deal->activities()->create([
            'type' => $validated['type'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'user_id' => Auth::id(),
            'deal_stage_id' => $deal->deal_stage_id,
        ]);

        // 4. Redirección de vuelta al pipeline de deals
        return redirect()->route('deals.index')->with('success', '¡Actividad registrada en el deal!');
    }
}