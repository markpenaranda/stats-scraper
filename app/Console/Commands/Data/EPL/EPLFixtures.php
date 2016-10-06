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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fixture = new Fixture;

        $fixtures = $fixture->all();


        foreach ($fixtures as $item) {
            $match = Match::firstOrNew(['match_url' => $item['url']]);
            $match->schedule = $item['schedule'];
            $match->match_url = $item['schedule'];
            $match->save();

            foreach($item['teams'] as $remarks => $value) {
                $team = Team::where('url', $value['url'])->first();

                $match->teams()->attach($team, ['remarks' => $remarks   ]);
            }
        }

    }
}
