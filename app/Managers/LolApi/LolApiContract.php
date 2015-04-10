<?php namespace URFBattleground\Managers\LolApi;

use URFBattleground\Managers\LolApi\Api\Challenge;

interface LolApiContract {

	/** @return Challenge */
	public function apiChallenge();

}
