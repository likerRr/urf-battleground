<?php namespace URFBattleground\Managers\RiotApi;

use URFBattleground\Managers\RiotApi\Api\ApiChallenge;
use URFBattleground\Managers\RiotApi\Contracts\RiotApi as RiotApiContract;
use URFBattleground\Managers\RiotApi\StaticData\Region;

class RiotApi implements RiotApiContract {

	/**
	 * @var Region
	 */
	private $region;

	/**
	 * @return ApiChallenge
	 */
	public function apiChallenge()
	{
		return new Api\ApiChallenge($this->getGlobalRegion());
	}

	public function setGlobalRegion($region)
	{
		$this->region = new Region($region);

		return $this;
	}

	public function getGlobalRegion()
	{
		return $this->region;
	}

}
