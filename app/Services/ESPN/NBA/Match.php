<?php
namespace App\Services\ESPN\NBA;

use App\Services\ESPN\BaseService;

class Match extends BaseService{

	public function all($date, $handler) {
		
			$schedules = [];
			$date = date('Ymd', strtotime($date));
			//for fetching game stats


			$url  = "http://cdn.espn.go.com/core/nba/schedule/_/date/" . $date . "?xhr=1&render=true&userab=0";


			 $nba_sched_json = $this->get($url);

			  if(!array_key_exists('content', $nba_sched_json)){
            	return $schedules;
        		}   
			 $games = $nba_sched_json['content']['schedule'][$date]['games'];

			 $handler->createBar(count($games));

			 foreach ($games as $game) {
			 	 $startDate = $game['competitions'][0]['startDate'];
			 	 $gameSchedule = strtotime($startDate) * 1000;

			 	 $competitors = $game['competitions'][0]['competitors'];
			 	 $home_team = $competitors[0]['team'];
			 	 $away_team = $competitors[1]['team'];
			 	 dump('all teams complete ' . $game['uid']);
			 	 $time = "18:00:00";
			 	 if($game['status']['type']['shortDetail'] != "Final"){
			 	  	$sched_detail =  explode(" - ", $game['status']['type']['shortDetail']);
				 }
			 	 $status = ($game['status']['type']['completed']) ? 'Final' : 'Upcoming';
			 	 $teams = [
			 	 	'home' => $home_team['location'] . " " . $home_team['name'],
			 	 	'away' => $away_team['location'] . " " . $away_team['name']

			 	 ];
			 	 $game_id = $game['id'];
			 	 $game_date = date('Y-m-d H:i:s');

			 	 $schedule = [
			 	 	'teams' => $teams,
			 	 	'status' => $status,
			 	 	'url' => "http://cdn.espn.go.com/core/nba/boxscore?gameId=". $game_id ."&xhr=1&render=false&userab=0",
			 	 	'time' => $time
			 	 ];

			 	 array_push($schedules, $schedule);
			 	 $handler->advanceBar();


			 }

			 return $schedules;


	}


	public function matchStats($url)
	{
		$game_stats_json = $this->get($url);

		$status = ($game_stats_json['content']['statusState'] == "post") ? "Final" : 'Upcoming';

		$boxscore = $game_stats_json['gamepackageJSON']['boxscore'];

		
			if($boxscore && array_key_exists('players', $boxscore) && in_array('players', $boxscore)){
				return array(
            		'status' => $status,
            		'players' => []
        		); 

        }
			$teams = $boxscore['players'];
			 $players_out = [];
			
			foreach ($teams as $team) {
				dump('here');
				$players = $team['statistics'][0]['athletes'];
				//dump('here at stats');
				
				foreach ($players as $player) {
					//dump($player);
					//dump('player_sectin');
					$stats = $player['stats'];
					//dump($stats);
					$espn_id = $player['athlete']['id'];
					$jersey_number = $player['athlete']['jersey'];
					$displayName = $player['athlete']['displayName'];
				
					if(count($stats) > 0){
					 $fg = explode("-", $stats[1]);
					 $three_fg = explode("-", $stats[2]);
					 $ft_fg = explode("-", $stats[3]);
					}
					else{
						$fg = [0,0];
						$three_fg = [0,0];
						$ft_fg = [0,0];
					}
				 	$player_out = array(
				 		'player_url' => $player['athlete']['links'][0]['href'],
	                    'minutes' => (isset($stats[0])) ? $stats[0] : 0,
	                    'fgm' => (isset($fg[0])) ? $fg[0] : 0,
	                    'fga' => (isset($fg[1])) ? $fg[1] : 0,
	                    'fgp' => ($fg[0] == 0) ? 0 : $fg[0]/$fg[1],
	                    '3pm' => (isset($three_fg[0])) ? $three_fg[0] : 0,
	                    '3pa' => (isset($three_fg[1])) ? $three_fg[1] : 0,
	                    '3pp' => ($three_fg[0] == 0) ? 0 : $three_fg[0]/$three_fg[1],
	                    'ftm' => (isset($fg[0])) ? $fg[0] : 0,
	                    'fta' => (isset($ft_fg[1])) ? $ft_fg[1] : 0,
	                    'ftp' => ($ft_fg[0] == 0) ? 0 :$ft_fg[0]/$ft_fg[1],
	                    'oreb' => (isset($stats[4])) ? $stats[4] : 0,
	                    'dreb' => (isset($stats[5])) ? $stats[5] : 0,
	                    'reb' => (isset($stats[6])) ? $stats[6] : 0,
	                    'ast' => (isset($stats[7])) ? $stats[7] : 0,
	                    'tov' => (isset($stats[10])) ? $stats[10] : 0,
	                    'stl' => (isset($stats[8])) ? $stats[8] : 0,
	                    'blk' => (isset($stats[9])) ? $stats[9] : 0,
	                    'pf' => (isset($stats[11])) ? $stats[11] : 0,
	                    'pts' => (isset($stats[13])) ? $stats[13] : 0
                    );
				 	$players_out[] = $player_out;
				 	// dump($players_out);
				}
			}


			  $game = array(
            'status' => $status,
            'players' => $players_out
        );
	//dump($game);
        return $game;
			
	}


}
