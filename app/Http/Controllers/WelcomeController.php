<?php namespace URFBattleground\Http\Controllers;

use URFBattleground\Managers\RiotApi\Contracts\RiotApi;
use URFBattleground\Managers\RiotApi\StaticData\Region;

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
	 * @var RiotApi
	 */
	private $riotApi;

	public function __construct(RiotApi $riotApi)
	{
		$this->riotApi = $riotApi->setGlobalRegion(Region::RU);
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$apiChallengeApi = $this->riotApi->apiChallenge();
		$apiChallengeApi->gameIds(176208763);
		return view('welcome');
	}

}
