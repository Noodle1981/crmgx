<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesReportController extends Controller
{

public function index(Request $request) // <-- Ahora inyectamos el Request
{
    $user = Auth::user();

    // 1. Obtenemos el año y el mes de la URL. Si no vienen, usamos los actuales.
    $year = (int) $request->input('year', now()->year);
    $month = (int) $request->input('month', now()->month);

    $selectedDate = now()->setYear($year)->setMonth($month);


    // Creamos un objeto Carbon para manejar la fecha seleccionada fácilmente
    $selectedDate = now()->setYear($year)->setMonth($month);

    // --- Consultas Base para el Período Seleccionado ---
    $dealsWonQuery = $user->deals()->where('status', 'won')->whereMonth('updated_at', $month)->whereYear('updated_at', $year);
    $dealsLostQuery = $user->deals()->where('status', 'lost')->whereMonth('updated_at', $month)->whereYear('updated_at', $year);

    // --- KPIs --- (La lógica es la misma, solo cambian las variables base)
    $totalWonValue = (clone $dealsWonQuery)->sum('value');
    $wonCount = (clone $dealsWonQuery)->count();
    $lostCount = (clone $dealsLostQuery)->count();
    $totalDealsClosed = $wonCount + $lostCount;
    $averageDealSize = $wonCount > 0 ? $totalWonValue / $wonCount : 0;
    $conversionRate = $totalDealsClosed > 0 ? ($wonCount / $totalDealsClosed) * 100 : 0;

    // --- Listas de Datos ---
    $wonDeals = (clone $dealsWonQuery)->with('client')->latest('updated_at')->get();
    $lostDeals = (clone $dealsLostQuery)->with('client')->latest('updated_at')->get();
    
    // --- Datos para los Desplegables del Filtro ---
    // Obtenemos todos los años únicos en los que se cerró un deal
    $availableYears = $user->deals()->whereIn('status', ['won', 'lost'])
                         ->selectRaw("strftime('%Y', updated_at) as year") // <-- La sintaxis de SQLite
                         ->distinct()
                         ->orderBy('year', 'desc')
                         ->pluck('year');

    // Pasamos todos los datos, incluyendo los necesarios para los filtros, a la vista.
    return view('reports.sales', compact(
        'totalWonValue', 'wonCount', 'lostCount', 'averageDealSize',
        'conversionRate', 'wonDeals', 'lostDeals',
        'selectedDate', 'availableYears', 'year', 'month'
    ));
}}