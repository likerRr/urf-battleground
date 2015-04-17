<?php namespace URFBattleground\Http\Controllers;

use Carbon\Carbon;
use URFBattleground\GameId;
use URFBattleground\Managers\Helpers;
use URFBattleground\Managers\LolApi\Exception\Response\ApiResponseException;
use URFBattleground\Managers\LolApi\Exception\Response\LimitExceedException;
use URFBattleground\Managers\LolApi\LimitManager;
use URFBattleground\Managers\LolApi\LolApi;
use URFBattleground\Managers\LolApi\Region;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/** @var LolApi */
	private $lolApi;

	public function __construct(LolApi $lolApi)
	{
		set_time_limit(0);
		// global region for all api's
		$this->lolApi = $lolApi
			->setRegion(Region::RU)
			->cache(99999);
		$this->middleware('guest');
	}

	private function getHours($hours = 1)
	{
		return 12 * $hours;
	}

	private function getDays($days = 1)
	{
		return $this->getHours($days * 24);
	}

	public function index(LolApi $lolApi)
	{
// first - 1427865900
// data for br "503746021,503728520,503728387,503728463,503728123,503727329,503728052,503728388,503728339,503727899,503728025,503728246,503728622,503728132,503728405,503728506"
//		$apiChallengeApi = $this->lolApi->apiChallenge();
		LimitManager::resetAllCounters();
		$apiChallengeApi = \LolApi::apiChallenge();
		$regions = Region::allExcept(Region::PBE);
		$regionsModel = \URFBattleground\Region::all();
		$regionsArr = [];
		foreach ($regionsModel as $reg) {
			$regionsArr[$reg->name] = $reg->id;
		}

//		$regions = ['ru'];
//		try {
		$oneHour = $this->getDays(1.5);
		$initialTimes = $oneHour;
		$times = $initialTimes;
		$lastStamp = 0;
		$startedAt = Carbon::now();
		var_dump('--------> STARTED IN ' . $startedAt->toDateTimeString() . ' <--------');
		$successes = 0;
		$total = 0;
		foreach ($regions as $region) {
			$time = \Cache::get('last_timestamp');
//			$time = 1428433500;
			if (!$time) {
				throw new \Exception('No timestamp specified');
			}
			$carbon = Carbon::createFromTimestamp($time);
			while ($times > 0) {
				try {
//					var_dump('---- ' . $region . ' at ' . $carbon->toDateTimeString() . ' ----');
					// set local region for challenge api's
					$response = $apiChallengeApi
						->setRegion($region)
//							->notCache()
//							->getResource()
						->gameIds($carbon->getTimestamp());
					$data = $response->getData();
					$insertData = [];
					foreach ($data as $gameId) {
						$insertData[] = [
							'game_id' => $gameId,
							'region_id' => $regionsArr[$region],
							'receive_at' => $carbon->getTimestamp(),
							'created_at' => new \DateTime,
							'updated_at' => new \DateTime,
						];
					}
					\DB::table('games_ids')->insert($insertData);
					$successes++;
//						var_dump($response->getResource(), $response->json());
//					$response2 = $apiChallengeApi->repeatLast();
//					var_dump($response2->getResource(), $response2->json());
				} catch (LimitExceedException $e) {
//					var_dump('repeat for ' . $region . ' at ' . $carbon->getTimestamp());
					$resp = $apiChallengeApi->repeatLastUntilLimitPasses();
//						var_dump($resp->getResource(), $resp->json());
//					echo 'has exited after repeat';
					$successes++;
				} catch (ApiResponseException $e) {
//					var_dump($e->getMessage(), $e->getResponse()->asArray());
				} catch (\Exception $e) {
					var_dump("An exception: {$e->getMessage()}");
					Helpers::logException($e);
				}
				$carbon->addMinutes(5);
				$times -= 1;
				if ($lastStamp < $carbon->getTimestamp()) {
					$lastStamp = $carbon->getTimestamp();
				}
				$total++;
			}
			$times = $initialTimes;
		}
		\Cache::put('last_timestamp', $lastStamp, 99999);
		var_dump('Successes: ' . $successes . '/' . $total);
		var_dump('--------> FINISHED FOR ' . Carbon::instance($startedAt)->diffForHumans() . ' <--------');
		var_dump('--------> ENDED IN ' . Carbon::now()->toDateTimeString() . ' <--------');
//		var_dump(Region::$availableRegions);
		var_dump('last stamp - ' . $lastStamp);
		var_dump('That\'s it!');
		return view('welcome');
	}

}
