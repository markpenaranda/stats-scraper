<?php

namespace App\Console\Commands\Data\EPL;

use Illuminate\Console\Command;
use App\Services\PremiereLeague\Fixture;
use App\Services\PremiereLeague\Player as EplPlayer;
use App\Match;
use App\Team;
use App\PlayerMatchStats;
class EplGameStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'epl:game_stats';

    /**
     * The console command description.
     *
     * @var string
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

      $eplFixture = new Fixture();
      $eplPlayer = new EplPlayer();


        $startDate = strtotime(date("Y-m-d") . " -3 days") * 1000;
        $endDate = strtotime(date("Y-m-d") . " +1 days") * 1000;

        $this->info($startDate);
        $this->info($endDate);

        $matches = Match::where('schedule', '>', $startDate)->where('schedule', '<',  $endDate)->where('status', '!=', "Final")->where('league', 'epl')->get();

        

        foreach ($matches as $match) {
            $match->status = "Live";
            $eplFixture->init($match->match_url);
            // Get Match Status
            if($eplFixture->checkIfFinal()) {
              $match->status = "Final";
            }


            $scores = $eplFixture->getScore();

          	foreach ($match->teams as $team) {

          		foreach ($team->roster as $player) {
          			$playerMatchStats = PlayerMatchStats::firstOrNew(['match_id' => $match->id, 'player_id' => $player->id]);
          			
                $fail = false;
                $failCounter = 0;
                  do {
                    $stats = $eplPlayer->careerStats($player->url, $player->position);

              			if($player->season_stats) {
                      
              				$currentStats = $player->season_stats->total_stats;


              				$gameStats = array();

              				foreach ($currentStats as $key => $value) {
                        dump($stats[$key] . " - " . $value);
                        //check here if fail
                        $currentValue = (int) $value;
                        $newValue     = (int) $stats[$key];
                        if($newValue < $currentValue) { $fail = true; $failCounter++; }
                        $gameStats[$key] = (int) $stats[$key] - (int) $value;

              				}

                    }
                } while($fail && $failCounter < 5);

          			$playerMatchStats->match_id = $match->id;
          			$playerMatchStats->player_id = $player->id;
          			$playerMatchStats->stats = json_encode($gameStats);
          			$playerMatchStats->save();
          		}
            $match->teams()->updateExistingPivot($team->id, ['score' => $scores[$team->pivot->remarks]]);

          }



          $match->save();

        }

    }
}
