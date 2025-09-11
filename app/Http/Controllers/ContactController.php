<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Rules\ValidPhoneNumber;



class ContactController extends Controller
{
    /**
     * Show the form for creating a new contact for a specific client.
     */
    public function create(Client $client)
    {
        // Seguridad: El cliente debe pertenecer al usuario.
        if (Auth::user()->id !== $client->user_id) {
            abort(403);
        }
        
        // Muestra la vista del formulario, pasándole el cliente padre.
        return view('contacts.create', compact('client'));
    }

    /**
     * Store a newly created contact in storage.
     * (Lógica adaptada de tu controlador de API)
     */
    public function store(Request $request, Client $client)
    {
        // Seguridad: El cliente debe pertenecer al usuario.
        if (Auth::user()->id !== $client->user_id) {
            abort(403);
        }

        // Validación (la misma que tenías en la API, ¡perfecta!)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:contacts,email',
             'phone' => ['nullable', 'string', 'max:255', new ValidPhoneNumber], // <-- ¡REGLA MEJORADA!
            'position' => 'nullable|string',
        ]);

        // Creación del contacto (la misma lógica)
        $client->contacts()->create($validated);

        // La gran diferencia: Redirigimos al usuario a la ficha del cliente
        // con un mensaje de éxito, en lugar de devolver JSON.
        return redirect()->route('clients.show', $client)->with('success', '¡Contacto añadido con éxito!');
    }


    public function setPhoneAttribute($value)
{
    if (empty($value)) {
        $this->attributes['phone'] = null;
        return;
    }

    $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
    try {
        $phoneNumber = $phoneUtil->parse($value, 'AR');
        $this->attributes['phone'] = $phoneUtil->format($phoneNumber, \libphonenumber\PhoneNumberFormat::E164);
    } catch (\libphonenumber\NumberParseException $e) {
        $this->attributes['phone'] = $value;
    }
}

        public function edit(Client $client, Contact $contact)
    {
        // La seguridad la podemos añadir aquí o dejar que el scoped() la maneje implícitamente
        return view('contacts.edit', compact('client', 'contact'));
    }

    public function update(Request $request, Client $client, Contact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('contacts')->ignore($contact->id)],
            'phone' => 'nullable|string',
            'position' => 'nullable|string',
        ]);

        $contact->update($validated);

        return redirect()->route('clients.show', $client)->with('success', '¡Contacto actualizado con éxito!');
    }

    public function destroy(Client $client, Contact $contact)
    {
        $contact->delete();
        return redirect()->route('clients.show', $client)->with('success', '¡Contacto eliminado con éxito!');
    }

    
}