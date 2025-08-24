<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // --- ¡AÑADE ESTA LÍNEA AQUÍ DENTRO! ---
        // Esto le dice a Laravel: "Ejecuta el comando 'app:process-sequences'
        // todos los días a las 7 de la mañana".
        $schedule->command('app:process-sequences')->dailyAt('07:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}