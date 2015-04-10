<?php namespace URFBattleground\Http\Controllers;

use Carbon\Carbon;
use URFBattleground\Managers\Helpers;
use URFBattleground\Managers\LolApi\LolApiContract;
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

	/**
	 * @var LolApiContract
	 */
	private $lolApi;

	public function __construct(LolApiContract $lolApi)
	{
		// global region for all api's
		$this->lolApi = $lolApi->setRegion(Region::RU);
		$this->middleware('guest');
	}

	public function index()
	{
// first - 1427865900
//		$apiChallengeApi = $this->lolApi->apiChallenge();
		$apiChallengeApi = \LolApi::apiChallenge();
		$regions = array_keys(Region::all());
		try {
			$time = 1427865900;
			$carbon = Carbon::createFromTimestamp($time);
//			$times = 3;
//			while ($times > 0) {
//				foreach ($regions as $region) {
					// set local region for challenge api's
					$response = $apiChallengeApi->setRegion('ru')->gameIds($carbon->getTimestamp());
					var_dump($response->getResource(), $response->json());
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
