<?php

namespace App\Console\Commands\Data\NBA;

use Illuminate\Console\Command;
use App\Services\ESPN\NBA\Team as DataTeam;
use App\Team;
use App\Player;
use App\PlayerMatchStats;
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


                $stats = CareerStats::firstOrNew([
                                                'player_id' => $player->id
                                            ]);
                $stats->player_id = $player->id;
                $stats->total_stats = json_encode($this->computeTotalStats($player));
                $stats->save();

            }

        }

        $this->info('Done');
    }

    private function computeTotalStats($player) 
    {
        $matchStats = PlayerMatchStats::where('player_id', $player->id)->get();

        $totalStats = [
            'appearances' => 0,
            'minutes' => 0,
            'fgm' => 0,
            'fga' => 0,
            'fgp' => 0,
            '3pm' => 0,
            '3pa' => 0,
            '3pp' => 0,
            'ftm' => 0,
            'fta' => 0,
            'ftp' => 0,
            'oreb' => 0,
            'dreb' => 0,
            'reb' => 0,
            'ast' => 0,
            'tov' => 0,
            'stl' => 0,
            'blk' => 0,
            'pf' => 0,
            'pts' => 0,
            "double_double" => 0,
            "triple_double"=> 0
        ];

        //"minutes":"19","fgm":"2","fga":"9","fgp":0.22222222222222,"3pm":"1","3pa":"4","3pp":0.25,"ftm":"2","fta":"4","ftp":1,"oreb":"1","dreb":"3","reb":"4","ast":"3","tov":"3","stl":"0","blk":"1","pf":"1","pts":"9"


        foreach ($matchStats as $match) {
            $statsPerGame = $match->stats;
            foreach ($statsPerGame as $key => $value) {
                if(array_key_exists($key, $totalStats)) {
                    $totalStats[$key] += $value;
                }
            }
            $totalStats['appearances'] += 1;
        }

        return $totalStats;
    }
}
