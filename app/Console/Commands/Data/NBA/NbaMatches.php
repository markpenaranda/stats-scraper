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

        $dateRange = $this->createDateRangeArray("2016-10-01", "2016-10-19");

        foreach ($dateRange as $date) {
            $this->info("\n" . $date . "\n");
            # code...
            $fixtures = $fixture->all($date, $this);
            foreach ($fixtures as $item) {
                $this->info("\n Saving in DB");
                $match = Match::firstOrNew(['match_url' => $item['url']]);
                $match->schedule = $item['schedule'];
                $match->match_url = $item['url'];
                $match->league = "nba";
                $match->status = $item['status'];
                $match->save();

                foreach($item['teams'] as $remarks => $value) {
                    $team = Team::where('name', $value)->where('league', 'nba')->first();

                    $match->teams()->attach($team, ['remarks' => $remarks   ]);
                }
            }

            $this->bar->finish();
        }



    }

    private function createDateRangeArray($strDateFrom,$strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }
}
