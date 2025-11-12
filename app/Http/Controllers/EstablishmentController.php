<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Establishment;
use Illuminate\Http\Request;
use App\Http\Requests\EstablishmentRequest;

class EstablishmentController extends Controller
{
    /**
     * Muestra la vista de mapa para un establecimiento.
     */
    public function map(Client $client, Establishment $establishment)
    {
        return view('establishments.map', compact('client', 'establishment'));
    }
    /**
     * Display a listing of all resources.
     */
    public function indexAll()
    {
        $establishments = Establishment::with('client', 'contacts')->get()->groupBy('client_id');
        return view('establishments.index', compact('establishments'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Client $client)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Client $client)
    {
        $contacts = $client->contacts()->get();
        return view('establishments.create', compact('client', 'contacts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EstablishmentRequest $request, Client $client)
    {
        $validated = $request->validated();

        $establishment = $client->establishments()->create($validated);
        
        // Actualizar los contactos seleccionados
        if (!empty($validated['contacts'])) {
            foreach ($validated['contacts'] as $contactId) {
                $contact = $client->contacts()->find($contactId);
                if ($contact) {
                    $contact->update(['establishment_id' => $establishment->id]);
                }
            }
        }

        return redirect()->route('clients.show', $client)->with('success', '¡Sede creada con éxito!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client, Establishment $establishment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client, Establishment $establishment)
    {
        $contacts = $client->contacts()->get();
        return view('establishments.edit', compact('client', 'establishment', 'contacts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EstablishmentRequest $request, Client $client, Establishment $establishment)
    {
        $validated = $request->validated();

        $establishment->update($validated);
        
        // Desasociar todos los contactos actuales del establecimiento
        $client->contacts()->where('establishment_id', $establishment->id)->update(['establishment_id' => null]);
        
        // Asociar los contactos seleccionados
        if (!empty($validated['contacts'])) {
            foreach ($validated['contacts'] as $contactId) {
                $contact = $client->contacts()->find($contactId);
                if ($contact) {
                    $contact->update(['establishment_id' => $establishment->id]);
                }
            }
        }

        return redirect()->route('clients.show', $client)->with('success', '¡Sede actualizada con éxito!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client, Establishment $establishment)
    {
        $establishment->delete();

        return redirect()->route('clients.show', $client)->with('success', '¡Sede eliminada con éxito!');
    }
}
