<?php

namespace App\Services\PremiereLeague;

use App\Team as DBTeam;

class Fixture extends BaseService{

	public function all()
	{
		$matches = [];
		while(count($matches) == 0) {
			$fixtureCrawler = $this->render("https://www.premierleague.com/fixtures", true);
			// $handler->createBar(count($fixtureCrawler->find('li.matchFixtureContainer')));
			foreach ($fixtureCrawler->find('li.matchFixtureContainer') as $matchContainer) {
				$crawledMatchUrl = $matchContainer->find('a.fixture', 0)->href;

				// dump($crawledMatchUrl);
				$matchUrl = "https:" . $crawledMatchUrl;

				$matchInfoCrawler = $this->render($matchUrl, true);


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

				if(!in_array($match, $matches)) {
					dump($match);
					array_push($matches, $match);
				}

				// $handler->advanceBar();
				
			}
		}

		// $handler->finishBar();



		return $matches;
	}

	public $renderedMatch;

	public function init($match_url)
	{
		dump($match_url);
		$this->renderedMatch = $this->render($match_url, true);
	}

	public function checkIfFinal()
	{
		$match = $this->renderedMatch;


		$lastComment = $match->find('ul.commentaryContainer', 0);
		if($lastComment) {
			$lastComment = $lastComment->find('li',0);
		}
		if($lastComment) {
			$lastComment = $lastComment->find('h6',0)->plaintext;
		}

		$lastComment = trim($lastComment);
		if ($lastComment == "Full Time!") {
			return true;
		}

		return false;


	}


	public function getScore() {
		$match = $this->renderedMatch;
		$score = $match->find('div.matchScoreContainer', 0);
		if($score) {
			$score = $score->find('div.score', 0);
			if($score) {
				$score = $score->plaintext;
				$arrayScore = explode("-", $score);

				return array(
						'home' => trim($arrayScore[0]),
						'away' => trim($arrayScore[1])
					);
			}
		}

		return array (
				'home' => 0,
				'away' => 0
			);

	}

}
