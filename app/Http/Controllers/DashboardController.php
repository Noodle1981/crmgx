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
        $activeLeadsCount = $user->leads()->whereIn('status', ['nuevo', 'contactado', 'calificado'])->count();


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
            'activeLeadsCount',
            'leadStatusCounts',
            'pipelineStats',
            'upcomingTasks',
            'recentDeals'
        ));
    }
        public function getPipelineData()
    {
        // Obtenemos todas las etapas en su orden correcto
        $stages = DealStage::orderBy('order')->get();
        $labels = [];
        $series = [];

        foreach ($stages as $stage) {
            // Para cada etapa, obtenemos el valor total de sus deals abiertos
            $value = $stage->deals()
                           ->where('user_id', auth()->id())
                           ->where('status', 'open')
                           ->sum('value');
            
            $labels[] = $stage->name;
            $series[] = $value;
        }

        return response()->json([
            'labels' => $labels,
            'series' => $series,
        ]);
    }

    /**
     * Muestra las estadísticas personales del usuario
     */
    public function personalStats()
    {
        $user = Auth::user();
        $data = [
            'leads_count' => Lead::where('user_id', $user->id)->count(),
            'deals_count' => Deal::where('user_id', $user->id)->count(),
            'tasks_count' => Task::where('user_id', $user->id)->count(),
        ];
        
        return view('dashboard.personal-stats', compact('data'));
    }

    /**
     * Muestra el pipeline personal del usuario
     */
    public function personalPipeline()
    {
        $user = Auth::user();
        $deals = Deal::where('user_id', $user->id)
                    ->with(['stage'])
                    ->get()
                    ->groupBy('stage.name');
        
        return view('dashboard.personal-pipeline', compact('deals'));
    }

    /**
     * Muestra las estadísticas del mes actual
     */
    public function currentMonth()
    {
        $user = Auth::user();
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        $data = [
            'new_leads' => Lead::where('user_id', $user->id)
                              ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                              ->count(),
            'closed_deals' => Deal::where('user_id', $user->id)
                                 ->where('status', 'won')
                                 ->whereBetween('closed_at', [$startOfMonth, $endOfMonth])
                                 ->count(),
            'completed_tasks' => Task::where('user_id', $user->id)
                                   ->where('status', 'completed')
                                   ->whereBetween('completed_at', [$startOfMonth, $endOfMonth])
                                   ->count(),
        ];
        
        return view('dashboard.current-month', compact('data'));
    }

}