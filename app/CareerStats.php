<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CareerStats extends Model
{
    protected $table = "career_stats";

    protected $fillable = ['player_id'];

    protected $visible = ['total_stats'];
    public function getTotalStatsAttribute($value) 
    {
    	return json_decode($value);
    }
}
