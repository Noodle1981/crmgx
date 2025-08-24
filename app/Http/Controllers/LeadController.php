<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    public function index()
    {
        // Mostramos solo leads que no estén 'convertido'
        $leads = Auth::user()->leads()
                     ->where('status', '!=', 'convertido')
                     ->latest()
                     ->paginate(15);
        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        return view('leads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:leads,email',
            'phone' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Auth::user()->leads()->create($validated + ['status' => 'nuevo']);

        return redirect()->route('leads.index')->with('success', '¡Lead creado con éxito!');
    }

    public function show(Lead $lead) { abort(404); }

    public function edit(Lead $lead)
    {
        if (Auth::user()->id !== $lead->user_id) abort(404);
        return view('leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        if (Auth::user()->id !== $lead->user_id) abort(404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('leads')->ignore($lead->id)],
            'phone' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(['nuevo', 'contactado', 'calificado', 'perdido'])],
            'notes' => 'nullable|string',
        ]);

        $lead->update($validated);

        return redirect()->route('leads.index')->with('success', '¡Lead actualizado con éxito!');
    }

    public function destroy(Lead $lead)
    {
        if (Auth::user()->id !== $lead->user_id) abort(404);
        $lead->delete();
        return redirect()->route('leads.index')->with('success', '¡Lead eliminado con éxito!');
    }
}