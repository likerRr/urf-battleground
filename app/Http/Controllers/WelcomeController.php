<?php namespace URFBattleground\Http\Controllers;

use Carbon\Carbon;
use URFBattleground\Managers\Helpers;
use URFBattleground\Managers\LolApi\Exception\Response\ApiResponseException;
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
		// global region for all api's
		$this->lolApi = $lolApi
			->setRegion(Region::RU)
			->store(1);
		$this->middleware('guest');
	}

	public function index(LolApi $lolApi)
	{
// first - 1427865900
//		$apiChallengeApi = $this->lolApi->apiChallenge();
		LimitManager::resetAllCounters();
		$apiChallengeApi = \LolApi::apiChallenge();
		$regions = Region::allExcept(Region::PBE);
//		$regions = ['ru'];
		try {
			$times = 1;

			foreach ($regions as $region) {
				$time = 1427865900;
				$carbon = Carbon::createFromTimestamp($time)->addDays(6);
				while ($times > 0) {
					var_dump('---- ' . $region . ' ----');
					// set local region for challenge api's
					$response = $apiChallengeApi->setRegion($region)->gameIds($carbon->getTimestamp());
					var_dump($response->getResource(), $response->json());
//					$response2 = $apiChallengeApi->repeatLast();
//					var_dump($response2->getResource(), $response2->json());
					$carbon->addMinutes(5);
				}
				$times -= 1;
			}
//			var_dump(Region::$availableRegions);
		} catch (ApiResponseException $e) {
			var_dump($e->getMessage());
			$apiChallengeApi->repeatLastUntilSuccess();
		} catch (\Exception $e) {
			var_dump($e->getMessage());
			Helpers::logException($e);
		}
		var_dump('That\'s it!');
		return view('welcome');
	}

}
