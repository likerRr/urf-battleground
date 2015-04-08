<?php namespace URFBattleground\Managers\RiotApi;

use URFBattleground\Managers\RiotApi\Api\Game;
use URFBattleground\Managers\RiotApi\Contracts\RiotApi as RiotApiContract;
use URFBattleground\Managers\RiotApi\StaticData\Region;

class RiotApi implements RiotApiContract {

	/**
	 * @var Region
	 */
	private $region;

	/**
	 * @return Game
	 */
	public function game()
	{
		// TODO check if region set globally
		// TODO add Region trait
		return new Api\Game($this->region->name());
	}

	public function setRegion($region)
	{
		$this->region = new Region($region);

		return $this;
	}

	public function getRegion()
	{
		return $this->region;
	}

}