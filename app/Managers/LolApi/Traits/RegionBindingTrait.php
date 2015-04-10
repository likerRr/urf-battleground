<?php namespace URFBattleground\Managers\LolApi\Traits;

use URFBattleground\Managers\LolApi\Region;

trait RegionBindingTrait {

	/** @var  Region */
	private $region;

	public function setRegion($region)
	{
		$this->region = ($region instanceof Region) ? $region : new Region($region);

		return $this;
	}

	public function getRegion()
	{
		return $this->region;
	}

}
