<?php namespace URFBattleground\Http\Controllers;

use Carbon\Carbon;
use URFBattleground\GameId;
use URFBattleground\Managers\Helpers;
use URFBattleground\Managers\LolApi\Api\Response\Response;
use URFBattleground\Managers\LolApi\Exception\Response\ApiResponseException;
use URFBattleground\Managers\LolApi\Exception\Response\LimitExceedException;
use URFBattleground\Managers\LolApi\Exception\Response\NotFoundException;
use URFBattleground\Managers\LolApi\Exception\UnexpectedException;
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
//		$apiChallengeApi = $this->lolApi->apiChallenge();
//		LimitManager::resetAllCounters();
		$apiChallengeApi = \LolApi::apiChallenge();
		$regions = Region::allExcept(Region::PBE);
		$regionsModel = \URFBattleground\Region::all();
		$time = \Cache::get('last_timestamp', 1428094800);
//		$time = 1428094800;
		$lastRegionId = GameId::where('receive_at', '<=', $time)->orderBy('id', 'desc')->first()->region_id;
		if (empty($lastRegionId)) {
			$lastRegionId = time();
		}

		$regionsArr = [];
		foreach ($regionsModel as $reg) {
			$regionsArr[$reg->name] = $reg->id;
		}
		$firstIt = count($regions);
		for ($i = $firstIt - 1; $i >= 0; $i--) {
			$regF = $regions[$i];
			if ($i === ($firstIt - 1) && $regionsArr[$regF] === $lastRegionId) {
				break;
			}
			if ($regionsArr[$regF] < $lastRegionId) {
				unset($regions[$i]);
			}
		}

//		$regions = ['ru'];
//		try {
		$initialTimes = $this->getDays(0.5);
		$times = $initialTimes;
		$lastStamp = 0;
		$startedAt = Carbon::now();
		$this->dump('STARTED IN ' . $startedAt->toDateTimeString() . '');
		$successes = 0;
		$total = 0;
		foreach ($regions as $region) {
			if (!$time) {
				throw new \Exception('No timestamp specified');
			}
			$carbon = Carbon::createFromTimestamp($time);
			$this->dump("Start requests for \"{$region}\" region -> " . $carbon->toDateTimeString());
			while ($times > 0) {
				// set local region for challenge api's
				$result = $this->catchExceptionsOnCall(function() use ($apiChallengeApi, $regionsArr, $region, $carbon) {
					try {
						return $apiChallengeApi
							->setRegion($region)
//								->notCache()
//								->getResource()
							->gameIds($carbon->getTimestamp());
					} catch (LimitExceedException $e) {
						$this->dump(
							$e->getMessage() . ', sleep for ' . \LolApi::getReadyAfter() . ' second(s)...'
						);
						return $this->catchExceptionsOnCall(function() use ($apiChallengeApi, $regionsArr, $region, $carbon) {
							return $apiChallengeApi->repeatLastUntilLimitPasses();
						}, function($resp) use ($regionsArr, $region, $carbon) {
							if (!$resp instanceof Response) {
								return false;
							}
							return $this->saveResponse($resp, $regionsArr[$region], $carbon->getTimestamp());
						});
					}
				}, function($response) use ($regionsArr, $region, $carbon) {
					if (!$response instanceof Response) {
						return false;
					}
					return $this->saveResponse($response, $regionsArr[$region], $carbon->getTimestamp());
				});
				$successes += ($result ? 1 : 0);

				$carbon->addMinutes(5);
				$times -= 1;
				if ($lastStamp < $carbon->getTimestamp()) {
					$lastStamp = $carbon->getTimestamp();
					\Cache::put('last_timestamp', $lastStamp, 99999);
				}
				$total += 1;
			}
			$this->dump(
				"End requests for \"{$region}\" region -> " . $carbon->toDateTimeString() . ', Successes: ' . $successes . ' / ' . $total
			);
			$times = $initialTimes;
		}
		$this->dump(
			'Successes: ' . $successes . '/' . $total,
			'--------> FINISHED ' . Carbon::instance($startedAt)->diffForHumans() . ' <--------',
			'Last stamp: ' . $lastStamp,
			'That\'s it!'
		);
		return view('welcome');
	}

	private function catchExceptionsOnCall($func, $success = null)
	{
		/********************
		 * ???? ??( ?-??) *
		 *******************/
		if (is_callable($func)) {
			try {
				$result = $func();
				if (!is_callable($success)) {
					return $result;
				}

				return $success($result);
			} catch (ApiResponseException $e) {
				$this->dump(
					"Api response exception: {$e->getMessage()}"
				);
				Helpers::logException($e);
			} catch (UnexpectedException $e) {
				$this->dump("An exception in LoL Engine: {$e->getMessage()}");
				Helpers::logException($e, ['module' => 'LE_Welcome']);
			} catch (\Exception $e) {
				$this->dump("An exception: {$e->getMessage()}");
				Helpers::logException($e, ['from' => 'WelcomeController::catchExceptionsOnCall']);
			}
		}

		return false;
	}

	private function dump($args)
	{
		$dateTimeString = Carbon::now()->toDateTimeString();
		var_dump("-----------------------------------------{$dateTimeString}-----------------------------------------");
		call_user_func_array('var_dump', func_get_args());
	}

	private function saveResponse(Response $response, $region, $received_at)
	{
		$data = $response->getData();
		$insertData = [];
		foreach ($data as $gameId) {
			$insertData[] = [
				'game_id' => $gameId,
				'region_id' => $region,
				'receive_at' => $received_at,
				'created_at' => new \DateTime,
				'updated_at' => new \DateTime,
			];
		}
		\DB::table('games_ids')->insert($insertData);

		return true;
	}

}
