<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerMatchStats extends Model
{
    protected $table = "player_match_stats";

    protected $fillable = ['match_id', 'player_id'];
    
    public function getStatsAttribute($value) 
    {
    	
    	return json_decode($value);
    }

    public function player() {
    	return $this->belongsTo('App\Player');
    }

   
}
