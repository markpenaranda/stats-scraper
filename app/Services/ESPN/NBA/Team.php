<?php 

namespace App\Services\ESPN\NBA;

use App\Services\ESPN\BaseService;

class Team extends BaseService{

	public function all() {
		$teamCrawler = $this->render("http://www.espn.com/nba/teams");
		$teams =[];

		foreach ($teamCrawler->find('div.logo-nba-medium') as $divTeam) {

			$href3 = $divTeam->find('a', 3)->href;
			$href = $divTeam->find('a.bi', 0)->href;
			$rosterUrl = str_replace("_", "roster/_", $href);
			// $ext = explode("/", $rosterUrl)[8];
			// $rosterUrl = str_replace($ext, "", $rosterUrl);

			$abbreviation = explode("=", $href3)[1];
			$team['name'] = $divTeam->find('a.bi', 0)->plaintext;
			$team['url'] = $divTeam->find('a.bi', 0)->href;
			$team['roster_url'] = $rosterUrl;
			$team['abbreviation'] = $abbreviation;
			$team['league'] = "nba";

			$team['image_url'] = "http://a.espncdn.com/combiner/i?img=/i/teamlogos/nba/500/". $abbreviation .".png?w=150&h=150&transparent=true";
			array_push($teams, $team);
			dump($team['name'] . "\n");
		}

		return $teams;

	}

	public function getRoster($team, $handler)
	{
		$crawler = $this->render($team->roster_url, true);
		$roster = [];

		$handler->createBar(count($crawler->find('tr.oddrow, tr.evenrow')));

		foreach ($crawler->find('tr.oddrow, tr.evenrow') as $playerRow) {
			$item['jersey_number'] = $playerRow->find('td', 0)->plaintext;
			$item['name'] = $playerRow->find('td', 1)->plaintext;
			$item['position'] = $playerRow->find('td', 2)->plaintext;
			$item['url'] = $playerRow->find('td', 1)->find('a', 0)->href;
		
			$item['country'] = $playerRow->find('td', 6)->plaintext;

			$espnId = explode("/", $item['url'])[7];

			$item['image_url'] = "http://a.espncdn.com/combiner/i?img=/i/headshots/nba/players/full/". $espnId . ".png&w=350&h=254";

			array_push($roster, $item);

			$handler->advanceBar();
		}


		$handler->finishBar();

		return $roster;


	}


}

