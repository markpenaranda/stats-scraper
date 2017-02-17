<?php namespace App\Services\CBA\Sina;


class Team extends BaseService
{
  public function all()
  {
    $url = "http://cba.sports.sina.com.cn/cba/team/all/?dpc=1";
    $page = $this->render($url);
    $teamListHtml = $page->find('.teamlist', 0);
    $teams = [];

    foreach ($teamListHtml->find('div.team_li') as $teamDiv) {
      $team['name'] = $teamDiv->find('h3', 0)->plaintext;
      $team['image_url'] = $teamDiv->find('.team_pic', 0)->find('img', 0)->src;
      $team['roster_url'] = $teamDiv->find('.team_pic', 0)->href;
      $team['url'] = $teamDiv->find('.team_pic', 0)->href;
      $team['abbreviation'] = $team['name'];
      array_push($teams, $team);
    }

    return $teams;
  }

  public function getRoster($team, $handler)
	{

		$crawler = $this->render($team->roster_url, false);
    $playerList = $crawler->find('div.content', 0);

		$roster = [];

		$handler->createBar(count($playerList->find('.people')));

		foreach ($playerList->find('.people') as $playerRow) {
      $playerPic  = $playerRow->find('a', 0);

      $item['image_url'] = $playerPic->find('img', 0)->src;
      $item['url'] = $playerPic->href;
      $item['name'] = trim($playerRow->find('span', 0)->plaintext);

      $playerHtml = $this->render($item['url']);
      $infoBase = $playerHtml->find('.info_base', 0);
      $jerseyPlainText = $infoBase->find('h3', 2)->find('span', 0)->plaintext;

      $jersey = preg_split('/：/', $jerseyPlainText);

			$item['jersey_number'] = trim($jersey[1]);

      $positionPlainText = $infoBase->find('h3', 2)->find('span', 1)->plaintext;
      $chinesePosition = preg_split('/：/', $positionPlainText)[1];
      $position = $this->getPosition(trim($chinesePosition));
			$item['position'] = $position;
			$item['country'] = "";


			array_push($roster, $item);

			$handler->advanceBar();
		}

    // dd($roster);

		$handler->finishBar();


		return $roster;


	}

  private function chineseToUnicode($str)
  {
    //split word
    preg_match_all('/./u',$str,$matches);

    $c = "";
    foreach($matches[0] as $m){
            $c .= "&#".base_convert(bin2hex(iconv('UTF-8',"UCS-4",$m)),16,10);
    }
    return $c;
  }

  private function mb_str_split( $string )
  {
    # Split at all position not after the start: ^
    # and not before the end: $
    return preg_split('/(?<!^)(?!$)/u', $string );
  }

  private function getPosition($chineseString)
  {
    if($chineseString == "前锋") {
      return "F";
    }
    if($chineseString == "后卫") {
      return "G";
    }
    if($chineseString == "中锋") {
      return "C";
    }

  }
}
