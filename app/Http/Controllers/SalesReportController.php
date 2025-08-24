<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Obtenemos y validamos las fechas del filtro
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);
        $selectedDate = now()->setYear($year)->setMonth($month);

        // --- 2. Consultas Base (SIN CLONAR TODAVÍA) ---
        $dealsWonThisMonthQuery = $user->deals()
                                       ->where('status', 'won')
                                       ->whereMonth('updated_at', $month)
                                       ->whereYear('updated_at', $year);

        $dealsLostThisMonthQuery = $user->deals()
                                        ->where('status', 'lost')
                                        ->whereMonth('updated_at', $month)
                                        ->whereYear('updated_at', $year);

        // --- 3. KPIs (Indicadores Clave de Rendimiento) con la LÓGICA CORREGIDA ---
        
        // Sumamos solo el valor de deals que son mayores a 0
        $totalWonValue = (clone $dealsWonThisMonthQuery)->where('value', '>', 0)->sum('value');
        
        // Contamos todos los deals ganados, sin importar su valor
        $wonCount = (clone $dealsWonThisMonthQuery)->count();
        
        // Contamos todos los deals perdidos
        $lostCount = (clone $dealsLostThisMonthQuery)->count();

        // ==========================================================
        // LÍNEA CORREGIDA
        // ==========================================================
        // Para el ticket promedio, solo contamos los deals con valor.
        // Se ha corregido el typo de `$dealsWonThisThisMonthQuery` a `$dealsWonThisMonthQuery`
        $wonCountWithValue = (clone $dealsWonThisMonthQuery)->where('value', '>', 0)->count();
        $averageDealSize = $wonCountWithValue > 0 ? $totalWonValue / $wonCountWithValue : 0;
        
        // La tasa de conversión se calcula sobre el total de deals cerrados
        $totalDealsClosed = $wonCount + $lostCount;
        $conversionRate = $totalDealsClosed > 0 ? ($wonCount / $totalDealsClosed) * 100 : 0;

        // --- 4. Listas de Datos (Para mostrar en las tablas) ---
        $wonDeals = (clone $dealsWonThisMonthQuery)->where('value', '>', 0)->with('client')->latest('updated_at')->get();
$lostDeals = (clone $dealsLostThisMonthQuery)->with('client')->latest('updated_at')->get();
        
        // --- 5. Datos para los Desplegables del Filtro ---
        $availableYears = $user->deals()->whereIn('status', ['won', 'lost'])
                             ->selectRaw("strftime('%Y', updated_at) as year")
                             ->distinct()
                             ->orderBy('year', 'desc')
                             ->pluck('year');

        // 6. Pasamos todas las variables a la vista
        return view('reports.sales', compact(
            'totalWonValue', 'wonCount', 'lostCount', 'averageDealSize',
            'conversionRate', 'wonDeals', 'lostDeals',
            'selectedDate', 'availableYears', 'year', 'month'
        ));
    }
}