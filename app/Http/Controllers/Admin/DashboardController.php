<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Deal;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Controlador para el panel de administración
 */

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'activeUsers' => User::where('is_active', true)->count(),
            'totalClients' => Client::count(),
            'activeDeals' => Deal::where('status', 'open')->count(),
            'pipelineValue' => Deal::where('status', 'open')->sum('value'),
        ];

        // Top vendedores del mes
        $topSellers = User::withCount(['deals as deals_count' => function($query) {
                $query->where('status', 'won')
                    ->whereMonth('closed_at', now()->month);
            }])
            ->withSum(['deals as deals_value' => function($query) {
                $query->where('status', 'won')
                      ->whereMonth('closed_at', now()->month);
            }], 'value')
            ->orderByDesc('deals_value')
            ->limit(5)
            ->get();

        // Conversión de leads
        $totalLeads = Lead::count() ?: 1; // Evitar división por cero
        $leadConversion = [
            ['name' => 'Nuevos', 'count' => Lead::where('status', 'nuevo')->count()],
            ['name' => 'Contactados', 'count' => Lead::where('status', 'contactado')->count()],
            ['name' => 'Calificados', 'count' => Lead::where('status', 'calificado')->count()],
            ['name' => 'Convertidos', 'count' => Lead::where('status', 'convertido')->count()],
        ];

        foreach ($leadConversion as &$status) {
            $status['percentage'] = round(($status['count'] / $totalLeads) * 100);
        }

        // Actividad reciente
        $recentActivity = Activity::with('user')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($activity) {
                $activity->type_color = $this->getActivityColor($activity->type);
                $activity->type_icon = $this->getActivityIcon($activity->type);
                return $activity;
            });

        return view('admin.dashboard', compact('stats', 'topSellers', 'leadConversion', 'recentActivity'));
    }

    private function getActivityColor($type)
    {
        return match ($type) {
            'deal_created' => 'bg-green-500',
            'deal_won' => 'bg-blue-500',
            'deal_lost' => 'bg-red-500',
            'lead_created' => 'bg-yellow-500',
            'lead_converted' => 'bg-purple-500',
            default => 'bg-gray-500',
        };
    }

    private function getActivityIcon($type)
    {
        return match ($type) {
            'deal_created' => 'fa-handshake',
            'deal_won' => 'fa-trophy',
            'deal_lost' => 'fa-times-circle',
            'lead_created' => 'fa-user-plus',
            'lead_converted' => 'fa-exchange-alt',
            default => 'fa-dot-circle',
        };
    }

    public function stats()
    {
        // Métricas por usuario
        $userMetrics = User::withCount([
            'deals as total_deals',
            'deals as won_deals' => function($query) {
                $query->where('status', 'won');
            },
            'leads as total_leads',
            'leads as converted_leads' => function($query) {
                $query->where('status', 'convertido');
            }
        ])
        ->withSum('deals as revenue', 'value')
        ->get();

        // Métricas por período
        $periodMetrics = [
            'daily' => $this->getPeriodMetrics('day'),
            'weekly' => $this->getPeriodMetrics('week'),
            'monthly' => $this->getPeriodMetrics('month')
        ];

        return view('admin.stats', compact('userMetrics', 'periodMetrics'));
    }

    private function getPeriodMetrics($period)
    {
        $startDate = now()->startOf($period);
        $endDate = now()->endOf($period);

        return [
            'new_deals' => Deal::whereBetween('created_at', [$startDate, $endDate])->count(),
            'won_deals' => Deal::where('status', 'won')
                              ->whereBetween('closed_at', [$startDate, $endDate])
                              ->count(),
            'new_leads' => Lead::whereBetween('created_at', [$startDate, $endDate])->count(),
            'converted_leads' => Lead::where('status', 'convertido')
                                   ->whereBetween('updated_at', [$startDate, $endDate])
                                   ->count(),
            'revenue' => Deal::where('status', 'won')
                           ->whereBetween('closed_at', [$startDate, $endDate])
                           ->sum('value')
        ];
    }

    public function logs()
    {
        $logs = Activity::with('user')
                      ->latest()
                      ->paginate(50);

        return view('admin.logs', compact('logs'));
    }
}