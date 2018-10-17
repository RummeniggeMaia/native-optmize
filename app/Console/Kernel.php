<?php

namespace App\Console;

use App\Campaingn;
use App\CampaingnLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        // $schedule->call(function () {
        //     $camps = Campaingn::with('campaignLogs')->where('type', 'CPA')->get();
        //     // foreach ($camps as $c) {
        //     //     $clicks
        //     // }
        // })
        // ->weekly()
        // ->tuesdays()
        // ->wednesdays()
        // ->thursdays()
        // ->fridays()->at('10:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
