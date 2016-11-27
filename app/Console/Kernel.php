<?php

namespace App\Console;

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
        'App\Console\Commands\Data\EPL\EplTeams',
        'App\Console\Commands\Data\EPL\EplRoster',
        'App\Console\Commands\Data\EPL\EplFixtures',
        'App\Console\Commands\Data\EPL\EplGameStats',
        'App\Console\Commands\Data\NBA\NbaTeams',
        'App\Console\Commands\Data\NBA\NbaRoster',
        'App\Console\Commands\Data\NBA\NbaMatches',
        'App\Console\Commands\Data\NBA\NbaGameStats',
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
        $schedule->command('nba:rosters')->daily();
        $schedule->command('nba:games')->daily();
        $schedule->command('nba:game_stats')->everyMinute();
        $schedule->command('epl:game_stats')->everyTenMinutes();
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
