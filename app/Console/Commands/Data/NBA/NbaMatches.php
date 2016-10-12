<?php

namespace App\Console\Commands\Data\NBA;

use Illuminate\Console\Command;
use App\Services\ESPN\NBA\Match as NbaMatch;
use App\Match;
use App\Team;

class NbaMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nba:games';

    /**
     * The console command description.
     *
     * @var string
     n
     */
    protected $description = 'Cron NBA Games';

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
        $fixture = new NbaMatch;

        $fixtures = $fixture->all(date("Y-m-d"), $this);


        foreach ($fixtures as $item) {
            $this->info("\n Saving in DB");
            $match = Match::firstOrNew(['match_url' => $item['url']]);
            $match->schedule = $item['schedule'];
            $match->match_url = $item['url'];
            $match->league = "nba";
            $match->save();

            foreach($item['teams'] as $remarks => $value) {
                $team = Team::where('name', $value)->where('league', 'nba')->first();

                $match->teams()->attach($team, ['remarks' => $remarks   ]);
            }
        }

        $this->bar->finish();

    }
}
