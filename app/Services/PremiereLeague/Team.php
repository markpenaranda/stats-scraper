<?php 

namespace App\Services\PremiereLeague;

use App\Team as DBTeam;

class Team extends BaseService{

	public $current_season = "54";
	public function all() 
	{
		$clubList = [];
		$crawler = $this->render("https://www.premierleague.com/clubs");


		foreach ($crawler->find('a.indexItem') as $club) {
			$url = "https://www.premierleague.com"  . $club->href;
			$name = $club->find('.clubName', 0)->plaintext;

			$clubProfile = $this->render($url);

			$imageUrl = $clubProfile->find('img.clubBadgeFallback', 0)->src;

			$rosterUrl = str_replace("overview", "squad", $url);
			array_push($clubList, ['name' => $name, 'url' => $url, 'image_url'=> 'http:' . $imageUrl, 'roster_url' => $rosterUrl]);
			dump($name . " registered");
		}

		return $clubList;

	}

	public function getRoster($team, $handler)
	{

		$crawler = $this->render($team->roster_url, true);

		$roster = [];

		$stats = [
			'goal' => 0,
			'assist' => 0,
			'shot' => 0,
			'shot_on_goal' => 0,
			'crosses' => 0,
			'fouls_drawn' => 0,
			'fouls_conceded' => 0,
			'tackle_won' => 0,
			'pass_intercepted' => 0,
			'yellow_card' => 0,
			'red_card' => 0,
			'penalty_kick_miss' => 0,
			'saves' => 0, // GK
			'goal_conceded' => 0, // GK
			'clean_sheet_gk' => 0, // GK
			'clean_sheet_d' => 0,
			'win' => 0, // GK
			'penalty_kick_save' => 0 // GK
		];

		$handler->createBar(count($crawler->find('a.playerOverviewCard')));

		foreach ($crawler->find('a.playerOverviewCard') as $player) {
			$item['name'] = trim($player->find('h4.name', 0)->plaintext);
			$item['jersey_number'] = trim($player->find('span.number', 0)->plaintext);
			$item['position'] = trim($player->find('span.position', 0)->plaintext);
			$item['country'] = trim($player->find('span.playerCountry', 0)->plaintext);
			$item['url'] = trim("https://www.premierleague.com" . $player->href);

			$statsUrl = str_replace("overview", "stats", $item['url']);

			$arrayUrl = explode("/", $statsUrl);
			
			$urlEncodedName = urlencode($arrayUrl[5]);

			$statsUrl = str_replace($arrayUrl[5], $urlEncodedName, $statsUrl);

			$playerStatsCrawler = $this->render($statsUrl . "?se=" . $this->current_season);
			
			$item['image_url'] = trim("https:" . $player->find('.statCardImg', 0)->src);

			$stats = [
				'appearances' => ($playerStatsCrawler->find('span[data-stat=appearances]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=appearances]', 0)->plaintext) : 0,
				'goal' => ($playerStatsCrawler->find('span[data-stat=goals]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=goals]', 0)->plaintext) : 0,
				'assist' => ($playerStatsCrawler->find('span[data-stat=goal_assist]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=goal_assist]', 0)->plaintext) : 0,
				'shot' => ($playerStatsCrawler->find('span[data-stat=total_scoring_att]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=total_scoring_att]', 0)->plaintext) : 0,
				'shot_on_goal' => ($playerStatsCrawler->find('span[data-stat=ontarget_scoring_att]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=ontarget_scoring_att]', 0)->plaintext) : 0,
				'crosses' => ($playerStatsCrawler->find('span[data-stat=total_cross]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=total_cross]', 0)->plaintext) : 0,
				// 'fouls_drawn' => 0,
				'fouls_conceded' => ($playerStatsCrawler->find('span[data-stat=fouls]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=fouls]', 0)->plaintext) : 0,
				'tackle_won' => ($playerStatsCrawler->find('span[data-stat=tackles]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=tackles]', 0)->plaintext) : 0,
				'pass_intercepted' => ($playerStatsCrawler->find('span[data-stat=interception]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=interception]', 0)->plaintext) : 0,
				'yellow_card' => ($playerStatsCrawler->find('span[data-stat=yellow_card]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=yellow_card]', 0)->plaintext) : 0,
				'red_card' => ($playerStatsCrawler->find('span[data-stat=red_card]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=red_card]', 0)->plaintext) : 0,
				// 'penalty_kick_miss' => 0,
				'saves' => ($playerStatsCrawler->find('span[data-stat=saves]', 0)) ? trim($playerStatsCrawler->find('span[data-stat=saves]', 0)->plaintext) : 0, // GK
				'goal_conceded' => ($playerStatsCrawler->find('span[data-stat=goals_conceded]', 0) && $item['position'] == "Goalkeeper") ? trim($playerStatsCrawler->find('span[data-stat=goals_conceded]', 0)->plaintext) : 0, // GK
				'clean_sheet_gk' => ($playerStatsCrawler->find('span[data-stat=clean_sheet]', 0) && $item['position'] == "Goalkeeper") ? trim($playerStatsCrawler->find('span[data-stat=clean_sheet]', 0)->plaintext) : 0, // GK
				'clean_sheet_d' => ($playerStatsCrawler->find('span[data-stat=clean_sheet]', 0) && $item['position'] == "Defender") ? trim($playerStatsCrawler->find('span[data-stat=clean_sheet]', 0)->plaintext) : 0,
				'win' => ($playerStatsCrawler->find('span[data-stat=wins]', 0) && $item['position'] == "Goalkeeper") ? trim($playerStatsCrawler->find('span[data-stat=wins]', 0)->plaintext) : 0, // GK
				'penalty_kick_save' => ($playerStatsCrawler->find('span[data-stat=penalty_save]', 0) && $item['position'] == "Goalkeeper") ? trim($playerStatsCrawler->find('span[data-stat=penalty_save]', 0)->plaintext) : 0 // GK
			];
				
			$item['career_stats'] = $stats;
			array_push($roster, $item);
			
			$handler->advanceBar();

		

		}

		$handler->finishBar();

		return $roster;

	}	

}