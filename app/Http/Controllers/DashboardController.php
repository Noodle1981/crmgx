<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // --- KPIs (Indicadores Clave de Rendimiento) ---
        $activeLeadsCount = $user->leads()->whereNotIn('status', ['convertido', 'perdido'])->count();
        $openDealsCount = $user->deals()->where('status', 'open')->count();
        $pipelineValue = $user->deals()->where('status', 'open')->sum('value');

        // --- Tareas Pendientes ---
        // Tareas que no están completadas y están ordenadas por fecha de vencimiento más próxima
        $upcomingTasks = $user->tasks()
                              ->where('status', '!=', 'completado')
                              ->orderBy('due_date', 'asc')
                              ->limit(5)
                              ->get();

        // --- Deals Recientes ---
        $recentDeals = $user->deals()
                            ->where('status', 'open')
                            ->with('client') // Carga el cliente para mostrar su nombre
                            ->latest() // Ordena por fecha de creación descendente
                            ->limit(5)
                            ->get();

        // Pasamos todos los datos a la vista
        return view('dashboard', compact(
            'activeLeadsCount',
            'openDealsCount',
            'pipelineValue',
            'upcomingTasks',
            'recentDeals'
        ));
    }
}