<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Establishment;
use Illuminate\Http\Request;

class EstablishmentController extends Controller
{
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
        return view('establishments.create', compact('client'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address_street' => 'required|string|max:255',
            'address_city' => 'required|string|max:255',
            'address_zip_code' => 'required|string|max:255',
            'address_state' => 'required|string|max:255',
            'address_country' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $client->establishments()->create($validated);

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
        return view('establishments.edit', compact('client', 'establishment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client, Establishment $establishment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address_street' => 'required|string|max:255',
            'address_city' => 'required|string|max:255',
            'address_zip_code' => 'required|string|max:255',
            'address_state' => 'required|string|max:255',
            'address_country' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $establishment->update($validated);

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
