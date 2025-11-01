<?php
namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);
        $selectedDate = now()->setYear($year)->setMonth($month);

        // Datos para los desplegables del filtro, comunes para ambos roles
        $availableYears = Deal::whereIn('status', ['won', 'lost'])
                             ->selectRaw("strftime('%Y', updated_at) as year")
                             ->distinct()
                             ->orderBy('year', 'desc')
                             ->pluck('year');

        // Si el usuario es administrador, preparamos un reporte global
        if ($user->is_admin) {
            return $this->adminReport($request, $selectedDate, $year, $month, $availableYears);
        }

        // Si no, preparamos el reporte personal
        return $this->personalReport($request, $user, $selectedDate, $year, $month, $availableYears);
    }

    private function personalReport(Request $request, User $user, $selectedDate, $year, $month, $availableYears)
    {
        $dealsWonQuery = $user->deals()->where('status', 'won')->whereMonth('updated_at', $month)->whereYear('updated_at', $year);
        $dealsLostQuery = $user->deals()->where('status', 'lost')->whereMonth('updated_at', $month)->whereYear('updated_at', $year);

        $totalWonValue = (clone $dealsWonQuery)->where('value', '>', 0)->sum('value');
        $wonCount = (clone $dealsWonQuery)->count();
        $lostCount = (clone $dealsLostQuery)->count();

        $wonCountWithValue = (clone $dealsWonQuery)->where('value', '>', 0)->count();
        $averageDealSize = $wonCountWithValue > 0 ? $totalWonValue / $wonCountWithValue : 0;

        $totalDealsClosed = $wonCount + $lostCount;
        $conversionRate = $totalDealsClosed > 0 ? ($wonCount / $totalDealsClosed) * 100 : 0;

        // Cálculo de la comisión
        $commissionRate = $user->settings['commission_rate'] ?? 0;
        $commissionAmount = $totalWonValue * ($commissionRate / 100);

        $wonDeals = (clone $dealsWonQuery)->where('value', '>', 0)->with('client')->latest('updated_at')->get();
        $lostDeals = (clone $dealsLostQuery)->with('client')->latest('updated_at')->get();

        return view('reports.sales', compact(
            'totalWonValue', 'wonCount', 'lostCount', 'averageDealSize',
            'conversionRate', 'commissionAmount', 'wonDeals', 'lostDeals',
            'selectedDate', 'availableYears', 'year', 'month'
        ));
    }

    private function adminReport(Request $request, $selectedDate, $year, $month, $availableYears)
    {
        $allDeals = Deal::whereIn('status', ['won', 'lost'])
                        ->whereMonth('updated_at', $month)
                        ->whereYear('updated_at', $year)
                        ->with('user') // Cargar el usuario para acceder a sus settings
                        ->get();

        $users = User::all()->keyBy('id');
        $reportData = [];

        $dealsByUser = $allDeals->groupBy('user_id');

        foreach ($dealsByUser as $userId => $userDeals) {
            if (!isset($users[$userId])) continue; // Omitir deals sin usuario (si es posible)

            $user = $users[$userId];
            $wonDeals = $userDeals->where('status', 'won');
            $lostDeals = $userDeals->where('status', 'lost');

            $totalWonValue = $wonDeals->where('value', '>', 0)->sum('value');
            $wonCount = $wonDeals->count();
            $lostCount = $lostDeals->count();

            $wonCountWithValue = $wonDeals->where('value', '>', 0)->count();
            $averageDealSize = $wonCountWithValue > 0 ? $totalWonValue / $wonCountWithValue : 0;

            $totalDealsClosed = $wonCount + $lostCount;
            $conversionRate = $totalDealsClosed > 0 ? ($wonCount / $totalDealsClosed) * 100 : 0;

            $commissionRate = $user->settings['commission_rate'] ?? 0;
            $commissionAmount = $totalWonValue * ($commissionRate / 100);

            $reportData[$userId] = [
                'userName' => $user->name,
                'wonCount' => $wonCount,
                'lostCount' => $lostCount,
                'totalWonValue' => $totalWonValue,
                'averageDealSize' => $averageDealSize,
                'conversionRate' => $conversionRate,
                'commissionAmount' => $commissionAmount,
            ];
        }

        // Ordenar por el valor total ganado de mayor a menor
        uasort($reportData, fn($a, $b) => $b['totalWonValue'] <=> $a['totalWonValue']);

        return view('reports.sales_admin', compact(
            'reportData', 'selectedDate', 'availableYears', 'year', 'month'
        ));
    }
}