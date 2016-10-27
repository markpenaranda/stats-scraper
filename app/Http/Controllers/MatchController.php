<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Match;
use App\PlayerMatchStats;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $league = $request->input('league');
        if ($request->has('date')) {            # code...
            $startDate = strtotime($request->input('date')) * 1000;
            $endDate = strtotime($request->input('date') . " +1 days") * 1000;
    	$matches =Match::where('schedule', '>', $startDate)->where('schedule', '<',  $endDate)->where('league', $league)->get();
        }
        else if($request->has('startTime') && $request->has('endTime')) {


            $startTime = $request->input('startTime');
            $endTime = $request->input('startTime');

            $matches =Match::where('schedule', '>', $startTime)->where('schedule', '<',  $endTime)->where('league', $league)->get();

        }
        else {
            $matches = Match::where('league', $league)->orderBy('id', 'desc')->get();
        }

    	return response()->json($matches);
    }

    public function getNextBatch()
    {

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
