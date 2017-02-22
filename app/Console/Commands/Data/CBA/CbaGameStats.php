<?php

namespace App\Console\Commands\Data\CBA;

use Illuminate\Console\Command;
use App\Services\CBA\Sina\Match as DataMatch;
use App\Match;
use App\Team;
use App\PlayerMatchStats;
use App\Player;
class CbaGameStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cba:game_stats';

    /**
     * The console command description.
     *
     * @var string
     n
     */
    protected $description = 'Cron CBA Game Stats';

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
    	$cbaMatch = new DataMatch();
 
        $startDate = strtotime(date("Y-m-d") . " -5 days") * 1000;
        $endDate = strtotime(date("Y-m-d") . " +1 days") * 1000;

        $matches = Match::where('schedule', '<',  $endDate)->where('league', 'cba')->get();

        foreach ($matches as $match) {
            dump($match->match_url);
          	$stats = $cbaMatch->matchStats($match->match_url);
            dump($stats);
            $bar = $this->output->createProgressBar(count($stats['players']));
            $match->status = $stats['status'];

            foreach ($stats['teams'] as $teamStats) {
                $team = Team::where('url', $teamStats['url'])->first();

                $match->teams()->updateExistingPivot($team->id, ['score' => $teamStats['score']]);
            }

            foreach ($stats['players'] as $player) {
                $dbPlayer = Player::where('url', 'like', $player['player_url'] . "%")->first();
                if($dbPlayer) {


                    $doubleDigitCounter = 0;

                    foreach($player as $stat => $statValue) {
                        if(in_array($stat, ['pts', 'reb', 'ast', 'stl', 'blk'])) {
                            if($statValue >= 10) {
                                ++$doubleDigitCounter;
                            } 
                        }
                    }

                    $player['double_double'] = ($doubleDigitCounter == 2) ? 1 : 0;

                    $player['triple_double'] = ($doubleDigitCounter > 2) ? 1 : 0;

                    $playerMatchStats = PlayerMatchStats::firstOrNew(['match_id' => $match->id, 'player_id' => $dbPlayer->id]);
                        $playerMatchStats->match_id = $match->id;
              			$playerMatchStats->player_id = $dbPlayer->id;
              			$playerMatchStats->stats = json_encode($player);
              			$playerMatchStats->save();
                    $bar->advance();
                    
                }
            }

            $match->save();
            $bar->finish();
          		
          	
           
        }

    }
}
