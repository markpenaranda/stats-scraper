<?php

namespace App\Console\Commands\Data\EPL;

use Illuminate\Console\Command;
use App\Services\PremiereLeague\Team as DataTeam;
use App\Team;
use App\Player;
use App\CareerStats;
class EplRoster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'epl:rosters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron EPL Teams Roster';

    public $bar;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function createBar($count) 
    {
        $this->bar = $this->output->createProgressBar($count);
    }

    public function advanceBar()
    {
        $this->bar->advance();
    }

    public function finishBar()
    {
        $this->bar->finish();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = new DataTeam;
        $teams = Team::where('league', 'epl')->get();
        foreach ($teams as $team) {
            $this->info("\n" . $team->name);
            $roster = $data->getRoster($team, $this);

            foreach ($roster as $item) {
               
                $player = Player::firstOrNew([
                    'name' => $item['name'],
                    'jersey_number' => $item['jersey_number'],
                    'country' => $item['country'],
                    'url' => $item['url']]);
                $player->name = $item['name'];
                $player->jersey_number = $item['jersey_number'];
                $player->position = $item['position'];
                $player->country = $item['country'];
                $player->url = $item['url'];
                $player->team_id = $team->id;
                $player->save();

                $career_stats = json_encode($item['career_stats']);

                $stats = new CareerStats();
                $stats->total_stats = $career_stats;

                $player->career_stats()->save($stats);
            }

        }

        $this->info('Done');
    }
}
