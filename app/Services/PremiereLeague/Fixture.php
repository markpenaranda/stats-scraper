<?php 

namespace App\Services\PremiereLeague;

use App\Team as DBTeam;

class Fixture extends BaseService{

	public function all($handler) 
	{
		$matches = [];
		while(count($matches) == 0) {
			$fixtureCrawler = $this->render("https://www.premierleague.com/fixtures", true);
			$handler->createBar(count($fixtureCrawler->find('li.matchFixtureContainer')));
			foreach ($fixtureCrawler->find('li.matchFixtureContainer') as $matchContainer) {
				$crawledMatchUrl = $matchContainer->find('a.fixture', 0)->href;
				
				// dump($crawledMatchUrl);
				$matchUrl = "https:" . $crawledMatchUrl;

				$matchInfoCrawler = $this->render($matchUrl, true);

				dump($matchContainer->{'data-comp-match-item-ko'});
			
				$scoreboxContainer = $matchInfoCrawler->find('div.scoreboxContainer', 0);

				
				$match = [
					'match_url' => $matchUrl,
					'schedule' => $matchContainer->{'data-comp-match-item-ko'},
					'teams' => [
						'home' => [
							'url' => $this->baseUrl . $scoreboxContainer->find('a.teamName', 0)->href
						],
						'away' => [
							'url' => $this->baseUrl . $scoreboxContainer->find('a.teamName', 1)->href
						]
					]
				];

				array_push($matches, $match);

				$handler->advanceBar();
				break;
			}
		}

		$handler->finishBar();



		return $matches;
	}

}