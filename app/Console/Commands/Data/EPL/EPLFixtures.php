<?php

namespace App\Console\Commands\Data\EPL;

use Illuminate\Console\Command;
use App\Services\PremiereLeague\Fixture;
use App\Match;
use App\Team;

class EplFixtures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'epl:fixtures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron EPL Teams Roster';

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
        $fixture = new Fixture;

        $fixtures = $fixture->all($this);

        foreach ($fixtures as $item) {
           
            $this->info("\n Saving in DB");
        
            $this->saveMatch($item);
            
        }

    }

    private function saveMatch(array $matchArray) 
    {
            $match = Match::firstOrNew(['match_url' => $matchArray['match_url']]);
            $match->schedule = $matchArray['schedule'];
            $match->match_url = $matchArray['match_url'];
            $match->league = "epl";
            $match->status = "Upcoming";
            $match->save();

            foreach($matchArray['teams'] as $remarks => $value) {
                $team_not_exist = true;
                $team = Team::where('url', $value['url'])->first();

                foreach ($match->teams as $added_team) {
                    if($added_team->id == $team->id) { $team_not_exist = false; }
                }
                if($team_not_exist) {
                    $match->teams()->attach($team, ['remarks' => $remarks   ]);
                }
            }
    }
}
