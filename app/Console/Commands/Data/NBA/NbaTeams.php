<?php

namespace App\Console\Commands\Data\NBA;

use Illuminate\Console\Command;
use App\Services\ESPN\NBA\Team as DataTeam;
use App\Team;
class NbaTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nba:teams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron NBA Teams';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dataTeam = new DataTeam;

        $teams = $dataTeam->all();

        foreach ($teams as $item) {
            $team = new Team();
            $team->name = $item['name'];
            $team->abbreviation = $item['abbreviation'];
            $team->url = $item['url'];
            $team->image_url = $item['image_url'];
            $team->roster_url = $item['roster_url'];
            $team->league = 'nba';
            $team->save();
        }

        $this->info('Done');
    }
}
