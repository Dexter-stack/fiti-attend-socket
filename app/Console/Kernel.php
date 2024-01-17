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
         $schedule->command('export:database')->weekly()->days([1,4])->at('8:50');
         
     }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected $commands = [
        Commands\ExportDatabase::class,

    ];
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
