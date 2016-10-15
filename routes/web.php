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

Route::get('/teams', 'TeamController@index');
Route::get('/teams/{id}', 'TeamController@show');

Route::get('/teams/{id}/roster', 'TeamController@showRoster');
Route::get('/players/{id}', 'PlayerController@show');



Route::get('/matches', 'MatchController@index');
Route::get('/matches/{id}', 'MatchController@matchStats');
