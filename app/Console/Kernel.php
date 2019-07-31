<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Reminder',
        'App\Console\Commands\InsertQuote'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       $schedule->command('daily:reminder')
                ->twiceDaily(8, 16);
       $schedule->command('command:insert_quote')
                ->hourly();

    }

    protected function commands()
   {
       require base_path('routes/console.php');
   }
}
