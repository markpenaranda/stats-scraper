<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = "teams";

    protected $visible = ['id','name', 'abbreviation', 'image_url', 'league'];

    public function roster() {
        return $this->hasMany('App\Player');
    }
}
