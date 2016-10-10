<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/{league}/teams', 'TeamController@index');
Route::get('/{league}/teams/{id}', 'TeamController@show');

Route::get('/{league}/teams/{id}/roster', 'TeamController@showRoster');
Route::get('/players/{id}', 'PlayerController@show');



Route::get('/{league}/matches', 'MatchController@index');
