<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Rules\ValidPhoneNumber;
use App\Http\Controllers\DealController;
use App\Http\Requests\ClientRequest;


class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::forCurrentUser();
        $filter = $request->get('filter', 'activos');
        if ($filter === 'inactivos') {
            $query = $query->where('client_status', 'inactivo');
        } else {
            $query = $query->where('client_status', '!=', 'inactivo');
        }
        // Para estadísticas, usar queries independientes para evitar duplicación y errores de brackets
        $stats = [
            'total_clients' => Client::forCurrentUser()->count(),
            'active_clients' => Client::forCurrentUser()->where('client_status', '!=', 'inactivo')->count(),
            'total_value' => Client::forCurrentUser()->withSum('deals', 'value')->get()->sum('deals_sum_value')
        ];
        $clients = $query->latest()->paginate(10);
        return view('clients.index', compact('clients', 'stats', 'filter'));
    }

    public function create()
    {
        return view('clients.create');
    }


    public function store(ClientRequest $request)
    {
        $validated = $request->validated();
        Auth::user()->clients()->create($validated);
        return redirect()->route('clients.index')->with('success', '¡Cliente creado con éxito!');
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
        'activities.user',

        // Carga los establecimientos
        'establishments'
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

    public function update(ClientRequest $request, Client $client)
    {
        if (Auth::user()->id !== $client->user_id) abort(404);
        $validated = $request->validated();
        $client->update($validated);
        return redirect()->route('clients.index')->with('success', '¡Cliente actualizado con éxito!');
    }

    public function destroy(Client $client)
    {
        if (Auth::user()->id !== $client->user_id) abort(404);
        $client->delete();
        return redirect()->route('clients.index')->with('success', '¡Cliente eliminado con éxito!');
    }

    public function data(Client $client)
    {
        if (Auth::user()->id !== $client->user_id) {
            abort(404);
        }

        $establishments = $client->establishments()->orderBy('name')->get();
        $contacts = $client->contacts()->orderBy('name')->get();

        return response()->json([
            'establishments' => $establishments,
            'contacts' => $contacts,
        ]);
    }

    public function deactivate(Client $client)
    {
        $client->update(['client_status' => 'inactivo']);
        // Registrar en el log
        activity()
            ->performedOn($client)
            ->causedBy(auth()->user())
            ->withProperties(['client_id' => $client->id, 'name' => $client->name])
            ->log('client_deactivated');
        return redirect()->route('clients.index')->with('success', 'Cliente desactivado correctamente.');
    }

    public function activate(Client $client)
    {
        $client->update(['client_status' => 'activo']);
        // Registrar en el log
        activity()
            ->performedOn($client)
            ->causedBy(auth()->user())
            ->withProperties(['client_id' => $client->id, 'name' => $client->name])
            ->log('client_activated');
        return redirect()->route('clients.index')->with('success', 'Cliente reactivado correctamente.');
    }
}