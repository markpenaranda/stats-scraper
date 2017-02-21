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
}