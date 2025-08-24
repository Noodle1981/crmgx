<?php

namespace App\Http\Controllers;

use App\Models\Sequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SequenceController extends Controller
{
    public function index()
    {
        $sequences = Auth::user()->sequences()->latest()->paginate(10);
        return view('sequences.index', compact('sequences'));
    }

    public function create()
    {
        return view('sequences.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Auth::user()->sequences()->create($validated);

        return redirect()->route('sequences.index')->with('success', '¡Secuencia creada con éxito!');
    }

    /**
     * La vista 'show' será la más importante, mostrará los pasos de la secuencia.
     */
    public function show(Sequence $sequence)
    {
        if (Auth::user()->id !== $sequence->user_id) abort(404);

        // Cargamos los pasos en orden
        $sequence->load('steps'); 

        return view('sequences.show', compact('sequence'));
    }

    public function edit(Sequence $sequence)
    {
        if (Auth::user()->id !== $sequence->user_id) abort(404);
        return view('sequences.edit', compact('sequence'));
    }

    public function update(Request $request, Sequence $sequence)
    {
        if (Auth::user()->id !== $sequence->user_id) abort(404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $sequence->update($validated);

        return redirect()->route('sequences.index')->with('success', '¡Secuencia actualizada con éxito!');
    }

    public function destroy(Sequence $sequence)
    {
        if (Auth::user()->id !== $sequence->user_id) abort(404);
        $sequence->delete();
        return redirect()->route('sequences.index')->with('success', '¡Secuencia eliminada con éxito!');
    }
}