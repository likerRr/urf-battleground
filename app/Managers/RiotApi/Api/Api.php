<?php  namespace URFBattleground\Managers\RiotApi\Api;

use URFBattleground\Managers\RiotApi\StaticData\Region;

abstract class Api {

	protected $apiVer;
	protected $dryUrl;

	/**
	 * Basic support of all regions
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
		Region::TR,
		Region::PBE,
	];

	/**
	 * @var Region
	 */
	protected $region;

	public function __construct($region)
	{
		if ($region instanceof Region) {
			$this->bindRegion($region);
			$this->isApiSupportsRegion();
		}
	}

	public function getPossibleRegions()
	{
		return $this->supportsRegions;
	}

	public function isRegionSupports()
	{
		return in_array($this->region->name(), $this->supportsRegions);
	}

	protected function isApiSupportsRegion()
	{
		if (!$this->isRegionSupports()) {
			throw new \Exception('API doesn\'t support region');
		}

		return true;
	}

	public function bindRegion(Region $region) {
		$this->region = $region;

		return $this;
	}

	public function setRegion($regionName)
	{
		$this->region = new Region($regionName);
		$this->isApiSupportsRegion();

		return $this;
	}

	public function getRegion()
	{
		return $this->region;
	}

	/**
	 * @param $dryUrl
	 * @return ApiRequest
	 */
	protected function initApiRequest($dryUrl) {
		return new ApiRequest($dryUrl, $this->region, $this->apiVer);
	}

}
