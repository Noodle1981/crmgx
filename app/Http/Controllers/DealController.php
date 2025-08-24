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
    /**
     * Muestra el pipeline de ventas.
     */
 public function index()
{
    $user = Auth::user();
    $dealStages = DealStage::orderBy('order')->get();

    $pipelineData = $dealStages->map(function ($stage) use ($user) {
        $deals = $stage->deals()
                       ->where('user_id', $user->id)
                       ->where('status', 'open') // <-- ¡LA LÍNEA MÁGICA Y CORRECTA!
                       ->with('client')
                       ->get();

        return [
            'id' => $stage->id,
            'name' => $stage->name,
            'deals' => $deals
        ];
    });

    return view('deals.index', ['pipelineData' => $pipelineData]);
}

    /**
     * Muestra el formulario para crear un nuevo deal.
     * El parámetro $client es opcional y vendrá de la ruta anidada.
     */
    public function create(Client $client = null)
    {
        // Si venimos de la ficha de un cliente específico, esa es nuestra única opción.
        // Si no, obtenemos todos los clientes del usuario para un <select> general.
        $clients = $client ? collect([$client]) : Auth::user()->clients()->orderBy('name')->get();
        
        // Pasamos a la vista la lista de clientes y el cliente que ya está seleccionado (si existe).
        return view('deals.create', [
            'clients' => $clients,
            'selectedClient' => $client 
        ]);
    }

    /**
     * Guarda un nuevo deal.
     */
    public function store(Request $request)
    {
        $userClientIds = Auth::user()->clients()->pluck('id')->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'client_id' => ['required', 'integer', Rule::in($userClientIds)],
        ]);

        $deal = Auth::user()->deals()->create([
            'name' => $validated['name'],
            'value' => $validated['value'],
            'client_id' => $validated['client_id'],
            'deal_stage_id' => 1,
            'status' => 'open',
        ]);

        // Si el formulario nos envió una señal de que venía de la ficha de cliente,
        // volvemos a la ficha del cliente. Si no, al pipeline.
        if ($request->input('from_client_show')) {
            return redirect()->route('clients.show', $deal->client_id)->with('success', '¡Deal añadido con éxito!');
        }

        return redirect()->route('deals.index')->with('success', '¡Nuevo deal creado con éxito!');
    }
    
    public function updateStage(Request $request, Deal $deal)
    {
        // Seguridad: ¿Este deal le pertenece al usuario?
        if (Auth::user()->id !== $deal->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validación: Asegurarse de que nos envían una etapa válida
        $validated = $request->validate([
            'deal_stage_id' => 'required|integer|exists:deal_stages,id'
        ]);

        // Actualización
        $deal->update([
            'deal_stage_id' => $validated['deal_stage_id']
        ]);

        // Redirección con mensaje de éxito
        return redirect()->back()->with('success', __('messages.deal_updated'));
    }
     public function markAsWon(Deal $deal)
    {
        if (auth()->user()->id !== $deal->user_id) abort(403);

        $deal->update(['status' => 'won']);

        return redirect()->route('deals.index')->with('success', "¡Felicidades! Has ganado el deal '{$deal->name}'.");
    }

    /**
     * Mark a deal as lost.
     */
    public function markAsLost(Deal $deal)
    {
        if (auth()->user()->id !== $deal->user_id) abort(403);
        
        $deal->update(['status' => 'lost']);
        
    return redirect()->route('deals.index')->with('success', "DEBUG: Deal '{$deal->name}' marcado como perdido.");
    }

    // ... los métodos edit, update, destroy y updateStage se quedan como estaban ...
    // Asegúrate de tenerlos aquí.
}