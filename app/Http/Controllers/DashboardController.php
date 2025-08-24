<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Task;
use Carbon\Carbon;
use App\Models\Client;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // --- KPIs de Resumen General ---
        $clientCount = $user->clients()->count();
        $contactCount = $user->contacts()->count(); // Necesitaremos añadir esta relación
        $activeSequencesCount = $user->sequenceEnrollments()->where('status', 'active')->count(); // Y esta
        
        // --- KPIs de Leads (Agrupados por estado) ---
        $leadStatusCounts = $user->leads()
                                ->select('status', DB::raw('count(*) as total'))
                                ->whereIn('status', ['nuevo', 'contactado', 'calificado', 'perdido'])
                                ->groupBy('status')
                                ->pluck('total', 'status');

        // --- KPIs de Pipeline (Agrupados en una sola variable) ---
        $pipelineStats = [
            'openDealsCount' => $user->deals()->where('status', 'open')->count(),
            'pipelineValue' => $user->deals()->where('status', 'open')->sum('value'),
            'wonDealsValue' => $user->deals()->where('status', 'won')->whereMonth('updated_at', now()->month)->sum('value'), // Ganado este mes
        ];

        // --- Listas de Acción ---
        $upcomingTasks = $user->tasks()
                              ->where('status', '!=', 'completado')
                              ->orderBy('due_date', 'asc')
                              ->limit(5)
                              ->get();

        $recentDeals = $user->deals()
                            ->where('status', 'open')
                            ->with('client')
                            ->latest()
                            ->limit(5)
                            ->get();

        // Pasamos todos los datos a la vista
        return view('dashboard', compact(
            'clientCount',
            'contactCount',
            'activeSequencesCount',
            'leadStatusCounts',
            'pipelineStats',
            'upcomingTasks',
            'recentDeals'
        ));
    }
}