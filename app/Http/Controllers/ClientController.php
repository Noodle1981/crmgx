<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\DealController;



class ClientController extends Controller
{
    public function index()
    {
        $clients = Auth::user()->clients()->latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email',
            'phone' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        Auth::user()->clients()->create($validated);
        return redirect()->route('clients.index')->with('success', '¡Cliente creado con éxito!');
    }



public function show(Client $client)
{
    // 1. Seguridad: Solo el dueño puede ver los detalles.
    if (Auth::user()->id !== $client->user_id) {
        abort(404);
    }

    // 2. Carga eficiente de TODAS las relaciones en una sola llamada.
    $client->load([
        // Carga los contactos, y por cada contacto, sus inscripciones, y por cada inscripción, la secuencia
        'contacts.sequenceEnrollments.sequence', 
        
        // Carga los deals, y por cada deal, su etapa
        'deals.stage', 
        
        // Carga los deals, y por cada deal, sus actividades, y por cada actividad, su usuario
        'deals.activities.user', 
        
        // Carga las actividades directas del cliente, y por cada actividad, su usuario
        'activities.user'
    ]);

    // --- El resto de la lógica para consolidar el historial se queda exactamente igual ---
    
    $clientActivities = $client->activities;
    $dealActivities = $client->deals->pluck('activities')->flatten();
    
    $fullActivityLog = $clientActivities->merge($dealActivities)
                                       ->sortByDesc('created_at');

    $client->setRelation('activities', $fullActivityLog->values());
    
    // 3. Pasamos el cliente (cargado con absolutamente todo) a la vista.
    return view('clients.show', compact('client'));
}

    public function edit(Client $client)
    {
        if (Auth::user()->id !== $client->user_id) abort(404);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        if (Auth::user()->id !== $client->user_id) abort(404);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('clients')->ignore($client->id)],
            'phone' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $client->update($validated);
        return redirect()->route('clients.index')->with('success', '¡Cliente actualizado con éxito!');
    }

    public function destroy(Client $client)
    {
        if (Auth::user()->id !== $client->user_id) abort(404);
        $client->delete();
        return redirect()->route('clients.index')->with('success', '¡Cliente eliminado con éxito!');
    }
}