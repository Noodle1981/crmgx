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
            $deals = $stage->deals()
                ->where('user_id', $user->id)
                ->where('status', 'open')
                ->with('client', 'activities.stage') // Cargar actividades y su etapa
                ->get();

            // Procesar cada deal para añadir el resumen de actividades
            $deals->each(function ($deal) {
                $activitySummary = $deal->activities
                    ->groupBy('stage.name') // Agrupar por nombre de la etapa
                    ->map(function ($activitiesInStage) {
                        return $activitiesInStage->countBy('type'); // Contar por tipo
                    });
                $deal->activity_summary = $activitySummary;
            });

            return ['id' => $stage->id, 'name' => $stage->name, 'deals' => $deals];
        });

        return view('deals.index', ['pipelineData' => $pipelineData]);
    }

    public function create(Client $client = null)
    {
        $clients = $client ? collect([$client]) : Auth::user()->clients()->orderBy('name')->get();
        $deal = new Deal();

        $establishments = collect([]);
        $contacts = collect([]);

        if ($client) {
            $establishments = $client->establishments()->orderBy('name')->get();
            $contacts = $client->contacts()->orderBy('name')->get();
        }

        return view('deals.create', [
            'clients' => $clients,
            'deal' => $deal,
            'selectedClient' => $client,
            'establishments' => $establishments,
            'contacts' => $contacts,
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
            'notes_contact' => 'nullable|string',
            'establishment_id' => 'nullable|exists:establishments,id',
            'primary_contact_id' => 'nullable|exists:contacts,id',
            'contacts' => 'nullable|array',
            'contacts.*' => 'exists:contacts,id',
        ]);

        $deal = Auth::user()->deals()->create([
            'name' => $validated['name'],
            'value' => $validated['value'],
            'client_id' => $validated['client_id'],
            'expected_close_date' => $validated['expected_close_date'] ?? null,
            'deal_stage_id' => 1,
            'status' => 'open',
            'notes_contact' => $validated['notes_contact'] ?? null,
            'establishment_id' => $validated['establishment_id'] ?? null,
            'primary_contact_id' => $validated['primary_contact_id'] ?? null,
        ]);

        if (isset($validated['contacts'])) {
            $deal->contacts()->attach($validated['contacts']);
        }

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
        if (Auth::user()->id !== $deal->user_id) {
            abort(403, 'Acción no autorizada.');
        }

        $deal->load('client.establishments', 'client.contacts');

        $clients = Auth::user()->clients()->orderBy('name')->get();
        $establishments = $deal->client->establishments()->orderBy('name')->get();
        $contacts = $deal->client->contacts()->orderBy('name')->get();

        return view('deals.edit', compact('deal', 'clients', 'establishments', 'contacts'));
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
            'notes_contact' => 'nullable|string',
            'notes_proposal' => 'nullable|string',
            'notes_negotiation' => 'nullable|string',
            'primary_contact_id' => 'nullable|exists:contacts,id',
            'contacts' => 'nullable|array',
            'contacts.*' => 'exists:contacts,id',
        ]);

        $deal->update($validated);

        if (isset($validated['contacts'])) {
            $deal->contacts()->sync($validated['contacts']);
        } else {
            $deal->contacts()->detach();
        }

        return redirect()->route('deals.index')->with('success', '¡Deal actualizado con éxito!');
    }

    public function show(Deal $deal)
    {
        // Asegurarse que el usuario es el dueño del deal
        if (Auth::user()->id !== $deal->user_id) {
            abort(403, 'Acción no autorizada.');
        }

        // Cargar relaciones necesarias
        $deal->load('client', 'dealStage', 'primaryContact', 'contacts');

        $activities = $deal->activities()->with('user', 'stage')->orderBy('created_at', 'desc')->get();

        $groupedActivities = $activities->groupBy('deal_stage_id');

        $dealStages = DealStage::whereIn('id', $groupedActivities->keys())->get()->keyBy('id');


        return view('deals.show', compact('deal', 'groupedActivities', 'dealStages'));
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

    $newStage = DealStage::find($validated['deal_stage_id']);

    // Lógica para guardar el valor original al mover a "Negociación"
    if ($newStage && $newStage->name === 'Negociación' && is_null($deal->original_value)) {
        $deal->original_value = $deal->value;
    }
    
    $deal->deal_stage_id = $validated['deal_stage_id'];
    $deal->save();

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