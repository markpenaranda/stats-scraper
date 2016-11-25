<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $table = "players";

    protected $fillable = ['name','jersey_number','country','url'];

    protected $visible = ['id','name','jersey_number','country', 'image_url', 'position', 'country', 'season_stats'];

    public function season_stats() 
    {
    	return $this->hasOne('App\CareerStats');
    }
}
