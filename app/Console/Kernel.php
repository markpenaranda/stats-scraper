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
        // EPL
        'App\Console\Commands\Data\EPL\EplTeams',
        'App\Console\Commands\Data\EPL\EplRoster',
        'App\Console\Commands\Data\EPL\EplFixtures',
        'App\Console\Commands\Data\EPL\EplGameStats',
        // NBA
        'App\Console\Commands\Data\NBA\NbaTeams',
        'App\Console\Commands\Data\NBA\NbaRoster',
        'App\Console\Commands\Data\NBA\NbaMatches',
        'App\Console\Commands\Data\NBA\NbaGameStats',
        // CBA
        'App\Console\Commands\Data\CBA\CbaTeams',
        'App\Console\Commands\Data\CBA\CbaRoster',
        'App\Console\Commands\Data\CBA\CbaMatches',
        'App\Console\Commands\Data\CBA\CbaGameStats',

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
        $schedule->command('epl:fixtures')->dailyAt('09:00');
        $schedule->command('epl:rosters')->dailyAt('10:00');
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
