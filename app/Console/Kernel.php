<?php

namespace App\Console;

use App\Campaingn;
use App\CampaingnLog;
use App\Postback;
use App\Click;
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
        $schedule->call(function () {
            $posts = Postback::with('clicks.creative')->get();
            foreach ($posts as $postback) {
                $postback->creative->yield = 0;
            }
            foreach ($posts as $postback) {
                $postback->creative->yield += $postback->amt;
            }
            foreach ($posts as $postback) {
                $logs = CreativeLog::with('campaingns')->where([
                    'creative_id' => $postback->creative->id,
                    'type' => 'CPA'
                ]);
                if (count($logs) > 0 && $logs[0]->impressions > 0) {
                    $postback->creative->ecpm = 
                        $postback->creative->yield 
                        / $logs[0]->impressions;
                }
            }
        })
        ->weekly()
        ->tuesdays()
        ->wednesdays()
        ->thursdays()
        ->fridays()->at('10:00');
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
