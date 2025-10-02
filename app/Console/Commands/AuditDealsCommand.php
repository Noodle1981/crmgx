<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Deal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // <-- AÑADIDO

class AuditDealsCommand extends Command
{
    protected $signature = 'crm:audit-deals';
    protected $description = 'Audits open deals to ensure they have a minimum number of activities in the last 30 days.';

    public function handle()
    {
        $this->info('Iniciando auditoría de seguimiento de deals abiertos...');
        Log::info('================ INICIO DE AUDITORÍA DE DEALS ================');

        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $openDeals = Deal::where('status', 'open')->get();
        $totalOpenDeals = $openDeals->count();
        $compliantDeals = 0;

        if ($totalOpenDeals === 0) {
            $this->info('No se encontraron deals con estado "abierto".');
            Log::info('No se encontraron deals abiertos.');
            return 0;
        }

        foreach ($openDeals as $deal) {
            Log::info("--- Auditando Deal ID: {$deal->id} ({$deal->name}) ---");

            // Hacemos la consulta directa a la base de datos para depurar
            $totalActivities = $deal->activities()->count();
            Log::info("Actividades totales encontradas para este deal (sin filtro de fecha): {$totalActivities}");

            $recentActivitiesCount = $deal->activities()->where('created_at', '>=', $thirtyDaysAgo)->count();
            Log::info("Actividades recientes encontradas (con filtro de fecha): {$recentActivitiesCount}");

            if ($recentActivitiesCount >= 3) {
                $compliantDeals++;
                Log::info("Resultado: CUMPLE");
            } else {
                Log::info("Resultado: NO CUMPLE");
            }
        }
        
        $compliancePercentage = ($totalOpenDeals > 0) ? ($compliantDeals / $totalOpenDeals) * 100 : 0;

        $this->newLine(2);
        $this->line('================ Auditoría Finalizada =================');
        $this->line("Deals abiertos totales: <fg=yellow>{$totalOpenDeals}</>");
        $this->line("Deals con seguimiento adecuado (>= 3 actividades en 30 días): <fg=yellow>{$compliantDeals}</>");
        $this->line(sprintf("Porcentaje de cumplimiento: <fg=yellow>%.2f%%</>", $compliancePercentage));
        $this->line('========================================================');
        $this->newLine();

        if ($compliancePercentage >= 90) {
            $this->info('[✓] Criterio de Adecuación Cumplido (>= 90%).');
        } else {
            $this->error('[✗] Criterio de Adecuación No Cumplido (< 90%).');
        }

        Log::info("Porcentaje final de cumplimiento: {$compliancePercentage}%");
        Log::info('================ FIN DE AUDITORÍA DE DEALS =================');

        return 0;
    }
}