<?php

namespace App\Console\Commands\Data\NBA;

use Illuminate\Console\Command;
use App\Services\ESPN\NBA\Team as DataTeam;
use App\Team;
use App\Player;
use App\CareerStats;
class NbaRoster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nba:rosters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron NBA Teams Roster';

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
        $teams = Team::where('league', 'nba')->get();
        foreach ($teams as $team) {
            $this->info("\n" . $team->name);
            Player::where('team_id', '=', $team->id)->update(['active' => false]);
            $roster = $data->getRoster($team, $this);

            foreach ($roster as $item) {

                $player = Player::firstOrNew([
                    'url' => $item['url']]);
                $player->name = $item['name'];
                $player->jersey_number = $item['jersey_number'];
                $player->position = $item['position'];
                $player->country = $item['country'];
                $player->url = $item['url'];
                $player->image_url = $item['image_url'];
                $player->active = true;
                $player->team_id = $team->id;
                $player->save();

            }

        }

        $this->info('Done');
    }
}
