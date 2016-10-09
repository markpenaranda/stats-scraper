<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Match;

class MatchController extends Controller
{
    public function index(Request $request, $league)
    {
    	$matches = Match::where('league', $league)->get();

    	return response()->json($matches);
    }
}
