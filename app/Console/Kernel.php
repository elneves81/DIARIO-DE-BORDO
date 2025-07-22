<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Backup diário às 2h da manhã
        $schedule->command('backup:database --compress')
                 ->dailyAt('02:00')
                 ->appendOutputTo(storage_path('logs/backup.log'));

        // Limpeza de logs semanalmente
        $schedule->command('logs:clean --days=30')
                 ->weekly()
                 ->sundays()
                 ->at('03:00');

        // Limpeza de cache ocasional
        $schedule->command('cache:clear')
                 ->weekly()
                 ->sundays()
                 ->at('04:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
