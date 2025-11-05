<?php

namespace App\Http\Controllers;

use App\Models\LeadReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadReminderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        $reminder = LeadReminder::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', '¡Recordatorio creado con éxito!');
    }

    public function complete(LeadReminder $reminder)
    {
        if (Auth::id() !== $reminder->user_id) {
            abort(403);
        }

        $reminder->complete();

        return back()->with('success', '¡Recordatorio marcado como completado!');
    }

    public function destroy(LeadReminder $reminder)
    {
        if (Auth::id() !== $reminder->user_id) {
            abort(403);
        }

        $reminder->delete();

        return back()->with('success', '¡Recordatorio eliminado!');
    }
}