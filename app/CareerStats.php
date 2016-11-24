<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CareerStats extends Model
{
    protected $table = "career_stats";

    protected $visible = ['total_stats', 'player_id'];
    public function getTotalStatsAttribute($value) 
    {
    	return json_decode($value);
    }
}
