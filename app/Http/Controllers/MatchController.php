<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Match;
use App\PlayerMatchStats;

class MatchController extends Controller
{
    public function index(Request $request, $league)
    {
    	$matches = Match::where('league', $league)->get();

    	return response()->json($matches);
    }

    public function matchStats(Request $request, $id)
    {
    	$match = Match::find($id);

    	$player_stats = [];
    	foreach ($match->player_stats as $record) {
    		$item['stats'] = $record->stats;
    		$item['player'] = $record->player;
    		$item['player_id'] = $record->player_id;

    		array_push($player_stats, $item);
    	}

    	$match['player_stats'] = $player_stats;

    	return response()->json($match);


    }
}
