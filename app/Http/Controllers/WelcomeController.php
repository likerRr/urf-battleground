<?php namespace URFBattleground\Http\Controllers;

use Carbon\Carbon;
use URFBattleground\Managers\Helpers;
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
		$apiChallengeApi = \LolApi::apiChallenge();
		$regions = array_keys(Region::all());
		try {
			$time = 1427865900;
			$carbon = Carbon::createFromTimestamp($time)->addDays(6);
//			$times = 3;
//			while ($times > 0) {
//				foreach ($regions as $region) {
					// set local region for challenge api's
					$response = $apiChallengeApi->setRegion('ru')->gameIds($carbon->getTimestamp());
					var_dump($response->getResource(), $response->json());
					$response2 = $apiChallengeApi->repeatLastRequest();
					var_dump($response2->getResource(), $response2->json());
//				}
//				$carbon->addMinutes(5);
//				$times -= 1;
//				var_dump('----');
//			}
//			var_dump(Region::$availableRegions);
		} catch (\Exception $e) {
			var_dump($e);
			Helpers::logException($e);
		}
		return view('welcome');
	}

}
