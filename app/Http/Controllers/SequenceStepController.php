<?php

namespace App\Http\Controllers;

use App\Models\Sequence;
use App\Models\SequenceStep;
use Illuminate\Http\Request;

class SequenceStepController extends Controller
{
    public function create(Sequence $sequence)
    {
        // Seguridad: ¿La secuencia pertenece al usuario?
        if (auth()->user()->id !== $sequence->user_id) abort(403);
        
        return view('sequences.steps.create', compact('sequence'));
    }

    public function store(Request $request, Sequence $sequence)
    {
        if (auth()->user()->id !== $sequence->user_id) abort(403);

        $validated = $request->validate([
            'type' => 'required|in:task,email',
            'delay_days' => 'required|integer|min:0',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        // Calculamos el orden del nuevo paso
        $order = ($sequence->steps()->max('order') ?? 0) + 1;

        $sequence->steps()->create($validated + ['order' => $order]);

        return redirect()->route('sequences.show', $sequence)->with('success', '¡Paso añadido a la secuencia!');
    }

    public function edit(Sequence $sequence, SequenceStep $step)
    {
        return view('sequences.steps.edit', compact('sequence', 'step'));
    }

    public function update(Request $request, Sequence $sequence, SequenceStep $step)
    {
        $validated = $request->validate([
            'type' => 'required|in:task,email',
            'delay_days' => 'required|integer|min:0',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        $step->update($validated);

        return redirect()->route('sequences.show', $sequence)->with('success', '¡Paso actualizado!');
    }

    public function destroy(Sequence $sequence, SequenceStep $step)
    {
        $step->delete();
        // Opcional: Reordenar los pasos restantes
        return redirect()->route('sequences.show', $sequence)->with('success', '¡Paso eliminado!');
    }
}