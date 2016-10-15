<?php

namespace App\Console\Commands\Data\NBA;

use Illuminate\Console\Command;
use App\Services\ESPN\NBA\Match as DataMatch;
use App\Match;
use App\Team;
use App\PlayerMatchStats;
use App\Player;
class NbaGameStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nba:game_stats';

    /**
     * The console command description.
     *
     * @var string
     n
     */
    protected $description = 'Cron EPL Game Stats';

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
    	$nbaMatch = new DataMatch();
 
        // $startDate = strtotime(date("Y-m-d")) * 1000;
        $startDate = 1475251200000;
        $endDate = strtotime(date("Y-m-d") . " +1 days") * 1000;

        $matches = Match::where('schedule', '>', $startDate)->where('schedule', '<',  $endDate)->where('league', 'nba')->get();

        foreach ($matches as $match) {
            dump($match->match_url);
          	$stats = $nbaMatch->matchStats($match->match_url);
            dump($stats);
            $bar = $this->output->createProgressBar(count($stats['players']));
            foreach ($stats['players'] as $player) {
                $dbPlayer = Player::where('url', 'like', $player['player_url'] . "%")->first();
                if($dbPlayer) {
                    $playerMatchStats = PlayerMatchStats::firstOrNew(['match_id' => $match->id, 'player_id' => $dbPlayer->id]);
                        $playerMatchStats->match_id = $match->id;
              			$playerMatchStats->player_id = $dbPlayer->id;
              			$playerMatchStats->stats = json_encode($player);
              			$playerMatchStats->save();
                    $bar->advance();
                    
                }
            }
            $bar->finish();
          		
          	
           
        }

    }
}
