<?php 

namespace App\Services\PremiereLeague;

use App\Team as DBTeam;

class Player extends BaseService {
	
	public $current_season = "54";

	public function careerStats($playerUrl, $position) 
	{

		$statsUrl = str_replace("overview", "stats", $playerUrl);


			$arrayUrl = explode("/", $statsUrl);
			
			$urlEncodedName = urlencode($arrayUrl[5]);

			$statsUrl = str_replace($arrayUrl[5], $urlEncodedName, $statsUrl);
			$fetchUrl = $statsUrl ."?se=" . $this->current_season;
			dump($fetchUrl);
			$playerStatsCrawler = $this->render($fetchUrl, true);
			$item['image_url'] = "https:" . $playerStatsCrawler->find('section.playerHero', 0)->find('img[data-script=pl_player-image]', 0)->src;
		    $stats = [
				'appearances' =>  ($playerStatsCrawler->find('span[data-stat=appearances]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=appearances]', 0)->plaintext) : 0,
				'goal' =>  ($playerStatsCrawler->find('span[data-stat=goals]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=goals]', 0)->plaintext) : 0,
				'assist' =>  ($playerStatsCrawler->find('span[data-stat=goal_assist]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=goal_assist]', 0)->plaintext) : 0,
				'shot' =>  ($playerStatsCrawler->find('span[data-stat=total_scoring_att]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=total_scoring_att]', 0)->plaintext) : 0,
				'shot_on_goal' =>  ($playerStatsCrawler->find('span[data-stat=ontarget_scoring_att]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=ontarget_scoring_att]', 0)->plaintext) : 0,
				'crosses' =>  ($playerStatsCrawler->find('span[data-stat=total_cross]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=total_cross]', 0)->plaintext) : 0,
				// 'fouls_drawn' =>  0,
				'fouls_conceded' =>  ($playerStatsCrawler->find('span[data-stat=fouls]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=fouls]', 0)->plaintext) : 0,
				'tackle_won' =>  ($playerStatsCrawler->find('span[data-stat=tackles]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=tackles]', 0)->plaintext) : 0,
				'pass_intercepted' =>  ($playerStatsCrawler->find('span[data-stat=interception]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=interception]', 0)->plaintext) : 0,
				'yellow_card' =>  ($playerStatsCrawler->find('span[data-stat=yellow_card]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=yellow_card]', 0)->plaintext) : 0,
				'red_card' =>  ($playerStatsCrawler->find('span[data-stat=red_card]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=red_card]', 0)->plaintext) : 0,
				// 'penalty_kick_miss' =>  0,
				'saves' =>  ($playerStatsCrawler->find('span[data-stat=saves]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=saves]', 0)->plaintext) : 0, // GK
				'goal_conceded' =>  ($playerStatsCrawler->find('span[data-stat=goals_conceded]', 0) && $position == "Goalkeeper") ? trim($playerStatsCrawler->find('span[data-stat=goals_conceded]', 0)->plaintext) : 0, // GK
				'clean_sheet_gk' =>  ($playerStatsCrawler->find('span[data-stat=clean_sheet]', 0) && $position == "Goalkeeper") ? trim($playerStatsCrawler->find('span[data-stat=clean_sheet]', 0)->plaintext) : 0, // GK
				'clean_sheet_d' =>  ($playerStatsCrawler->find('span[data-stat=clean_sheet]', 0) && $position == "Defender") ? trim($playerStatsCrawler->find('span[data-stat=clean_sheet]', 0)->plaintext) : 0,
				'win' =>  ($playerStatsCrawler->find('span[data-stat=wins]', 0) && $position == "Goalkeeper") ? trim($playerStatsCrawler->find('span[data-stat=wins]', 0)->plaintext) : 0, // GK
				'penalty_kick_save' =>  ($playerStatsCrawler->find('span[data-stat=penalty_save]', 0) && $position == "Goalkeeper") ? trim($playerStatsCrawler->find('span[data-stat=penalty_save]', 0)->plaintext) : 0 // GK
			];
				
			return $stats;

	}

}