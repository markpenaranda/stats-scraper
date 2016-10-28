<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $table = "matches";

    protected $fillable = ['match_url'];

    /* Additional Fields */

    protected $appends = ['competitors'];

   	protected $hidden = ['teams'];

    public function teams() 
    {
    	return $this->belongsToMany('App\Team', 'match_competitors', 'match_id', 'team_id')->withPivot('remarks', 'score');
    }

    public function player_stats()
    {
    	return $this->hasMany('App\PlayerMatchStats');
    }

    public function getCompetitorsAttribute()
    {
    	$output = [];


        foreach ($this->teams as $team) {
            $item['id'] = $team->id;
            $item['name'] = $team->name;
            $item['abbreviation'] = $team->abbreviation;
            $item['image_url'] = $team->image_url;
            $item['league'] = $team->league;
            $item['remarks'] = $team->pivot->remarks;
            $item['score'] = $team->pivot->score;
            array_push($output, $item);
        }

        return $output;
    }
}
