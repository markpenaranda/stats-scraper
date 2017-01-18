<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Team;
class TeamController extends Controller
{


    public function index(Request $request)
    {
        $league = $request->input('league');
    	$teams = Team::where('league', $league)->get();

    	return response()->json($teams);

    }

    public function show(Request $request, $league, $id)
    {
    	$team = Team::find($id);

    	return response()->json($team);

    }

    public function showRoster(Request $request, $id)
    {
    	$team = Team::find($id);
    	$item = [
    		'name' => $team->name,
    		'image_url' => $team->image_url,
    		'id' => $team->id,
    		'league' => $team->league,
    		'abbreviation' => $team->abbreviation,
    		'roster' => $team->roster->where('active', 1)->load('season_stats')
    	];

    	return response()->json($item);

    }
}
