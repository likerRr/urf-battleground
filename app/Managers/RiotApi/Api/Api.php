<?php  namespace URFBattleground\Managers\RiotApi\Api;

use URFBattleground\Managers\RiotApi\StaticData\Region;

class Api {

	protected $apiVer;
	protected $url;

	/**
	 * Default set of possible regions
	 * @var array
	 */
	protected $supportsRegions = [
		Region::BR,
		Region::EUNE,
		Region::EUW,
		Region::KR,
		Region::LAN,
		Region::LAS,
		Region::NA,
		Region::OCE,
		Region::RU,
		Region::TR
	];

	/**
	 * @var Region
	 */
	protected $region;

	public function __construct($region)
	{
		$this->setRegion($region);
		$this->checkRegionSupport();
	}

	public function getPossibleRegions()
	{
		return $this->supportsRegions;
	}

	public function isRegionSupports()
	{
		return in_array($this->region->name(), $this->supportsRegions);
	}

	public function checkRegionSupport()
	{
		if (!$this->isRegionSupports()) {
			throw new \Exception('Unsupported region');
		}
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

	public function request()
	{

	}

}