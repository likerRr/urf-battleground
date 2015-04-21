<?php namespace URFBattleground\Http\Controllers;

use Carbon\Carbon;
use Redis;
use URFBattleground\GameId;
use URFBattleground\Managers\CacheStorage;
use URFBattleground\Managers\Helpers;
use URFBattleground\Managers\LolApi\Api\Response\Dto\ListDto;
use URFBattleground\Managers\LolApi\Api\Response\Response;
use URFBattleground\Managers\LolApi\Api\Response\ResponseDto;
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
		ignore_user_abort(true);
//		ini_set('xdebug.max_nesting_level', 300);
		// global region for all api's
		$this->lolApi = $lolApi
			->setRegion(Region::RU)
			->throwOnLimitExceed()
			->autoRepeatOnLimitExceed()
			->cache(99999)
			->storage(new CacheStorage());
//			->autoRepeatOnLimitExceed(5);
//			->throwOnLimitExceed();
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

	private function updateDb()
	{
//		$db = \DB::connection('mysql.azure')->getReadPdo();
		// max id = 2725471
		$start = 2725471;
		$period = 100000;
		$end = $start - $period;
		$total = 0;
		$success = 0;
		$fail = 0;
		while ($start > 0) {
			$games = GameId::on('mysql.azure')->where('id', '<', $start)->where('id', '>=', $end)->get();
			if ($games->count() > 0) {
				foreach ($games as $game) {
					try {
						GameId::create([
							'game_id' => $game->game_id,
							'region_id' => $game->region_id,
							'receive_at' => $game->receive_at
						]);
						$success++;
					} catch (\Exception $e) {
						$this->dump($e->getMessage());
						$fail++;
					}
				}
			}
			$count = count($games);
			$start -= $period;
			$end -= $period;
			$total += $count;
		}

		$this->dump(
			"total: {$total}",
			"success: {$success}",
			"fail: {$fail}"
		);
		die;
	}

	public function index(LolApi $lolApi)
	{
		$this->catchExceptionsOnCall(function() use ($lolApi) {
			$match = $lolApi->apiMatch()->setRegion(Region::NA)->byId(1778704162, 1);
			var_dump($match);
//			var_dump($match->getResource());
//			dd($match->getData());
		});
		die;
//		$this->updateDb();
// first - 1427865900
//		$apiChallengeApi = $this->lolApi->apiChallenge();
//		LimitManager::resetAllCounters();
		$apiChallengeApi = \LolApi::apiChallenge();
		$regions = Region::allExcept(Region::PBE);
		$regionsModel = \URFBattleground\Region::all();
		// TODO check bad request response, e.g. for 1429697700
//		$time = \Cache::get('last_timestamp', 1428094800);
		$time = 1427865900;
//		$lastGame = GameId::where('receive_at', '<=', $time)->orderBy('id', 'desc')->first();
		$lastGame = '';
		if (empty($lastGame)) {
			$lastRegionId = 0;
		} else {
			$lastRegionId = $lastGame->region_id;
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
//				unset($regions[$i]);
			}
		}
		// TODO idleTime() - общее время простоя на лимитах
//		$regions = ['ru'];
//		try {
//		$initialTimes = $this->getDays(1);
		$initialTimes = 1;
//		$initialTimes = $this->getHours(1);
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
						$games = $apiChallengeApi
							->setRegion($region)
//								->preventCaching()
							->getResource()
							->gameIds($carbon->getTimestamp());

						return $games;
					} catch (LimitExceedException $e) {
						$this->dump(
							$e->getMessage() . ', sleep for ' . \LolApi::getReadyAfter() . ' second(s)...'
						);
						/** @var Response $respLimit */
						$respLimit = $this->catchExceptionsOnCall(function() use ($apiChallengeApi, $regionsArr, $region, $carbon) {
							return $apiChallengeApi->repeatLastUntilLimitPasses();
						});
//						var_dump('json after limit '.$respLimit->json());
						return $respLimit;
					}
				}, function($responseDto) use ($regionsArr, $region, $carbon) {
					if (!$responseDto instanceof ListDto) {
						return false;
					}
					return $this->saveResponse($responseDto, $regionsArr[$region], $carbon->getTimestamp());
				});
				$successes += ($result ? 1 : 0);

				$carbon->addMinutes(5);
				$times -= 1;
				if ($lastStamp < $carbon->getTimestamp()) {
					$lastStamp = $carbon->getTimestamp();
					\Cache::put('last_timestamp', $lastStamp, 99999);
				}
				$total += 1;
				$this->sendOutput();
			}
			$this->dump(
				"End requests for \"{$region}\" region -> " . $carbon->toDateTimeString() . ', Successes: ' . $successes . ' / ' . $total
			)->sendOutput();
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

	private function sendOutput()
	{
		$status = ob_get_status(true);
		$level = count($status);
		if (!empty($status[$level]['del'])
			|| (isset($status[$level]['flags'])
				&& ($status[$level]['flags'] & PHP_OUTPUT_HANDLER_REMOVABLE)
			)
		) {
			ob_end_flush();
		}
	}

	private function catchExceptionsOnCall($func, $success = null)
	{
		/********************
		 *  ┬──┬ ノ(゜-゜ノ) *
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

		return $this;
	}

	private function saveResponse(ListDto $response, $region, $received_at)
	{
		$data = $response->getList();
		if (!empty($data)) {
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
			$this->dump(
				'Resource ' . $response->response()->getResource(),
				'Saved ' . count($insertData) . ' games'
			);

			return true;
		}

		return false;
	}

}
