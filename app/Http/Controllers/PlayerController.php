<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Player;

class PlayerController extends Controller
{
    public function show(Request $request, $id) {
    	$player = Player::find($id);

    	$item = [
    		'name' => $player->name,
    		'id' => $player->id,
    		'image_url' => $player->image_url,
    		'jersey_number' => $player->jersey_number,
    		'country' => $player->country,
    		'position' => $player->position,
    		'season_stats' => $player->season_stats
    	];

    	return response()->json($item);
    }
}
