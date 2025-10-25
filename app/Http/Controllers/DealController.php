<?php

namespace App\Http\Controllers;

use App\Models\DealStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Deal;
use App\Models\Client;
use Illuminate\Validation\Rule;

class DealController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dealStages = DealStage::orderBy('order')->get();
        $pipelineData = $dealStages->map(function ($stage) use ($user) {
                        $deals = $stage->deals()->where('user_id', $user->id)->where('status', 'open')->with('client')->withCount('activities')->get();
            return ['id' => $stage->id, 'name' => $stage->name, 'deals' => $deals];
        });
        return view('deals.index', ['pipelineData' => $pipelineData]);
    }

    public function create(Client $client = null)
    {
        // Esta lógica es un poco compleja, pero la mantenemos si es necesaria para tu app.
        $clients = $client ? collect([$client]) : Auth::user()->clients()->orderBy('name')->get();
        
        // Creamos un deal vacío para que el formulario no de error al buscar `$deal->client_id`
        $deal = new Deal();

        return view('deals.create', [
            'clients' => $clients,
            'deal' => $deal, // Pasamos el deal vacío
            'selectedClient' => $client
        ]);
    }

    public function store(Request $request)
    {
        $userClientIds = Auth::user()->clients()->pluck('id')->toArray();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'client_id' => ['required', 'integer', Rule::in($userClientIds)],
            'expected_close_date' => 'nullable|date',
        ]);
        $deal = Auth::user()->deals()->create([
            'name' => $validated['name'],
            'value' => $validated['value'],
            'client_id' => $validated['client_id'],
            'expected_close_date' => $validated['expected_close_date'] ?? null,
            'deal_stage_id' => 1,
            'status' => 'open',
        ]);
        if ($request->input('from_client_show')) {
            return redirect()->route('clients.show', $deal->client_id)->with('success', '¡Deal añadido con éxito!');
        }
        return redirect()->route('deals.index')->with('success', '¡Nuevo deal creado con éxito!');
    }
    
    // ==========================================================
    // MÉTODO EDIT CORREGIDO Y SIMPLIFICADO
    // ==========================================================
    public function edit(Deal $deal)
    {
        // 1. Asegurarse que el usuario es el dueño del deal
        if (Auth::user()->id !== $deal->user_id) {
            abort(403, 'Acción no autorizada.');
        }
        
        // 2. Obtener la lista de todos los clientes del usuario
        $clients = Auth::user()->clients()->orderBy('name')->get();

        // 3. Simplemente pasar el 'deal' y los 'clients'. 
        // ¡No necesitamos $selectedClient! El formulario se encargará de todo.
        return view('deals.edit', compact('deal', 'clients'));
    }

    public function update(Request $request, Deal $deal)
    {
        if (Auth::user()->id !== $deal->user_id) {
            abort(403, 'Acción no autorizada.');
        }
        $userClientIds = Auth::user()->clients()->pluck('id')->toArray();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'client_id' => ['required', 'integer', Rule::in($userClientIds)],
            'expected_close_date' => 'nullable|date',
        ]);
        $deal->update($validated);
        return redirect()->route('deals.index')->with('success', '¡Deal actualizado con éxito!');
    }

        public function show(Deal $deal)
    {
        // Asegurarse que el usuario es el dueño del deal
        if (Auth::user()->id !== $deal->user_id) {
            abort(403, 'Acción no autorizada.');
        }

        // Cargar relaciones necesarias
        $deal->load('client', 'activities.user');

        return view('deals.show', compact('deal'));
    }

    public function destroy(Deal $deal)
    {
        if (Auth::user()->id !== $deal->user_id) {
            abort(403, 'Acción no autorizada.');
        }
        $deal->delete();
        return redirect()->route('deals.index')->with('success', '¡Deal eliminado con éxito!');
    }

    public function updateStage(Request $request, Deal $deal)
    {
        if (Auth::user()->id !== $deal->user_id) {
            abort(403, 'Acción no autorizada.');
        }
        $validated = $request->validate(['deal_stage_id' => 'required|integer|exists:deal_stages,id']);
        $deal->update(['deal_stage_id' => $validated['deal_stage_id']]);
        return redirect()->back()->with('success', __('messages.deal_updated'));
    }

public function markAsWon(Deal $deal)
{
    if (auth()->user()->id !== $deal->user_id) abort(403);

    // --- ¡NUEVA REGLA DE NEGOCIO! ---
    if ($deal->value <= 0 || is_null($deal->value)) {
        return redirect()->back()->with('error', 'No se puede marcar como ganado un deal sin un valor positivo. Por favor, edita el deal primero.');
    }
    // --- FIN DE LA REGLA ---

    $deal->update(['status' => 'won']);

    return redirect()->route('deals.index')->with('success', "¡Felicidades! Has ganado el deal '{$deal->name}'.");
}

    public function markAsLost(Deal $deal)
    {
        if (auth()->user()->id !== $deal->user_id) abort(403);
        $deal->update(['status' => 'lost']);
        return redirect()->route('deals.index')->with('success', "Deal '{$deal->name}' marcado como perdido.");
    }
}