<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $table = "players";

    protected $fillable = ['name','jersey_number','country','url'];

    public function career_stats() 
    {
    	return $this->hasOne('App\CareerStats');
    }
}
