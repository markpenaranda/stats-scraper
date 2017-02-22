<?php namespace App\Services\CBA\Sina;


class Match extends BaseService
{
	public $currentSinaLeagueId = 180;

	public function all($date, $handler)
	{
		$schedules = [];
		$month = date('m', strtotime($date));
		
		$url = "http://cba.sports.sina.com.cn/cba/schedule/all/?qleagueid=" . $this->currentSinaLeagueId ."&qmonth=" . $month . "&qteamid=";

		$page = $this->render($url);
		$content = $page->find(".content", 0);
		$container = $content->find(".blk", 1);
		$blkWrap = $container->find(".blk_wrap", 0);

		$table = $blkWrap->find("table", 0);
		
		foreach ($table->find("tr") as $row) {
			if($row->find('td', 0)) {
				$date = str_replace("&nbsp;", " ", $row->find('td', 1)->plaintext);
				$formattedDate = date("Y-m-d H:i:s", strtotime($date));
				$team_1 = $row->find('td', 2)->plaintext;
				$match_url = $row->find('td', 3)->find('a', 0)->href;
				$status = (trim($row->find('td', 3)->plaintext) == "VS") ? 'Upcoming' : 'Final';
				$team_2 = $row->find('td', 4)->plaintext;

				$schedule = [
					'teams' => [
							'home' => trim($team_1),
							'away' => trim($team_2),
						],
					'schedule' => strtotime($formattedDate) * 1000,
					'url' => $match_url,
					'status' => $status,
				];
				array_push($schedules, $schedule);	
			}
		}

		return $schedules;
	}

	public function matchStats($url)
	{
		$statsPage = $this->render($url);

		/**
		 *
		 * Team Score
		 *
		 */
		$teams_stats = [];
		$team_1_score = $statsPage->find('.team_scoreA', 0)->plaintext;
		$team_1_url = $statsPage->find('.score_pad', 0)->find('a', 0)->href;

		array_push($teams_stats, ['url' => $team_1_url, 'score' => trim($team_1_score)]);

		$team_2_score = $statsPage->find('.team_scoreB', 0)->plaintext;	
		$team_2_url = $statsPage->find('.score_pad', 0)->find('a', 1)->href;

		array_push($teams_stats, ['url' => $team_2_url, 'score' => trim($team_2_score)]);

		
		/**
		 *
		 * Box Score
		 *
		 */


		$team_1_boxscore = $statsPage->find('.part01', 0)->find('table', 0);

		foreach ($team_1_boxscore->find('tr') as $row) {
			if($row->find('td', 0) && $row->find('td', 0)->find('a', 0)) { 

				$ft = trim($row->find('td', 6)->plaintext);
				$ft = explode(" ", $ft);
				$freeThrow = explode("-", $ft[0]);

				$twofg = trim($row->find('td', 4)->plaintext);
				$twofg = explode(" ", $twofg);
				$twofgm = explode("-", $twofg[0]);


				$threefg = trim($row->find('td', 5)->plaintext);
				$threefg = explode(" ", $threefg);
				$threefgm = explode("-", $threefg[0]);

			

				$player_out = array(
			 		'player_url' => $row->find('td', 0)->find('a', 0)->href,
	                // 'minutes' => trim($row->find('td', 2)->plaintext),
	                'fgm' =>  $twofgm[0],
	                'fga' =>  $twofgm[1],
	                // 'fgp' =>  $threefg[0,
	                '3pm' => $threefgm[0],
	                '3pa' => $threefgm[1],
	                // '3pp' => ($three_fg[0] == 0) ? 0 : $three_fg[0]/$three_fg[1],
	                'ftm' => $freeThrow[0],
	                'fta' => $freeThrow[1],
	                // 'ftp' => ($ft_fg[0] == 0) ? 0 :$ft_fg[0]/$ft_fg[1],
	                'oreb' => trim($row->find('td', 7)->plaintext),
	                'dreb' => trim($row->find('td', 8)->plaintext),
	                'reb' => (int) trim($row->find('td', 7)->plaintext) + (int) trim($row->find('td', 8)->plaintext),
	                'ast' => trim($row->find('td', 9)->plaintext),
	                'tov' => trim($row->find('td', 12)->plaintext),
	                'stl' => trim($row->find('td', 11)->plaintext),
	                'blk' => trim($row->find('td', 13)->plaintext),
	                'pf' => trim($row->find('td', 10)->plaintext),
	                'pts' => trim($row->find('td', 17)->plaintext)
	            );

	           	$players_out[] = $player_out;
			 }
			// dd($row->plaintext);
		}


		$team_2_boxscore = $statsPage->find('.part02', 0)->find('table', 0);

		foreach ($team_2_boxscore->find('tr') as $row) {
			if($row->find('td', 0) && $row->find('td', 0)->find('a', 0)) { 

				$ft = trim($row->find('td', 6)->plaintext);
				$ft = explode(" ", $ft);
				$freeThrow = explode("-", $ft[0]);

				$twofg = trim($row->find('td', 4)->plaintext);
				$twofg = explode(" ", $twofg);
				$twofgm = explode("-", $twofg[0]);


				$threefg = trim($row->find('td', 5)->plaintext);
				$threefg = explode(" ", $threefg);
				$threefgm = explode("-", $threefg[0]);

			

				$player_out = array(
			 		'player_url' => $row->find('td', 0)->find('a', 0)->href,
	                // 'minutes' => trim($row->find('td', 2)->plaintext),
	                'fgm' =>  $twofgm[0],
	                'fga' =>  $twofgm[1],
	                // 'fgp' =>  $threefg[0,
	                '3pm' => $threefgm[0],
	                '3pa' => $threefgm[1],
	                // '3pp' => ($three_fg[0] == 0) ? 0 : $three_fg[0]/$three_fg[1],
	                'ftm' => $freeThrow[0],
	                'fta' => $freeThrow[1],
	                // 'ftp' => ($ft_fg[0] == 0) ? 0 :$ft_fg[0]/$ft_fg[1],
	                'oreb' => trim($row->find('td', 7)->plaintext),
	                'dreb' => trim($row->find('td', 8)->plaintext),
	                'reb' => (int) trim($row->find('td', 7)->plaintext) + (int) trim($row->find('td', 8)->plaintext),
	                'ast' => trim($row->find('td', 9)->plaintext),
	                'tov' => trim($row->find('td', 12)->plaintext),
	                'stl' => trim($row->find('td', 11)->plaintext),
	                'blk' => trim($row->find('td', 13)->plaintext),
	                'pf' => trim($row->find('td', 10)->plaintext),
	                'pts' => trim($row->find('td', 17)->plaintext)
	            );

	           	$players_out[] = $player_out;
			 }
			// dd($row->plaintext);
		}

		 $game = array(
            // 'status' => $status,
            'players' => $players_out,
            'teams' => $teams_stats
        );

		 return $game;


		
		



		
	}
}