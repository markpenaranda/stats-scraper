<?php

namespace App\Console\Commands\Data\EPL;

use Illuminate\Console\Command;
use App\Services\PremiereLeague\Team as DataTeam;
use App\Team;
class EplTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'epl:teams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron EPL Teams';

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
            $team->abbreviation = "NONE";
            $team->url = $item['url'];
            $team->image_url = $item['image_url'];
            $team->roster_url = $item['roster_url'];
            $team->league = 'epl';
            $team->save();
        }

        $this->info('Done');
    }
}
