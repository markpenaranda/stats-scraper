<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerMatchStats extends Model
{
    protected $table = "career_stats";

    protected $fillable = ['match_id', 'player_id'];
    public function getStatsAttribute($value) 
    {
    	return json_decode($value);
    }
}
