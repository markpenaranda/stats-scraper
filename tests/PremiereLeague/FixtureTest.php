<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\PremiereLeague\Fixture;
use App\Console\Commands\Data\EPL\EplFixtures;

class FixtureTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public $fixture, $eplFixtures;

    public function __construct() 
    {
    	$this->fixture 		= new Fixture;
    
    }

    public function testFetchUpcomingFixture()
    {
    	$all = $this->fixture->all();
    	dd($all);
        $this->assertTrue(is_array($all));
    }
}
