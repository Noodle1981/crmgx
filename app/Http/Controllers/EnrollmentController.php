<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Sequence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    public function create(Contact $contact)
    {
        // Seguridad: El cliente debe pertenecer al usuario.
        if (Auth::user()->id !== $contact->client->user_id) {
            abort(403);
        }
        $sequences = Auth::user()->sequences;
        return view('enrollments.create', compact('contact', 'sequences'));
    }

public function store(Request $request, Contact $contact)
    {
        // Seguridad: El cliente debe pertenecer al usuario.
        if (Auth::user()->id !== $contact->client->user_id) {
            abort(403);
        }

        // 1. Validamos la entrada
        $validated = $request->validate([
            'sequence_id' => 'required|exists:sequences,id'
        ]);

        // 2. BUSCAMOS la secuencia en la base de datos
        $sequence = Sequence::find($validated['sequence_id']);
        
        // 3. AHORA SÍ, comprobamos la seguridad de la secuencia
        if (Auth::user()->id !== $sequence->user_id) {
            abort(403);
        }
        
        // El resto de la lógica ya estaba bien...
        $firstStep = $sequence->steps()->orderBy('order')->first();
        
        if(!$firstStep) {
            return redirect()->back()->with('error', '¡Esa secuencia no tiene pasos!');
        }

        $contact->sequenceEnrollments()->create([
            'sequence_id' => $sequence->id,
            'user_id' => Auth::id(),
            'current_step_id' => $firstStep->id,
            'next_step_due_at' => Carbon::today()->addDays($firstStep->delay_days),
            'status' => 'active',
        ]);

        return redirect()->route('clients.show', $contact->client_id)->with('success', "¡{$contact->name} inscrito en la secuencia '{$sequence->name}'!");
    }
}