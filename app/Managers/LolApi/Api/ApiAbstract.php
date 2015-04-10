<?php  namespace URFBattleground\Managers\LolApi\Api;

use URFBattleground\Managers\LolApi\Region;

abstract class ApiAbstract {

	protected $apiVer;
	protected $dryUrl;
	/** @var Region */
	protected $region;

	protected $supportsRegions = [];

	public function __construct($region)
	{
		$this->region = $region;
		$this->isApiSupportsRegion();
	}

	public function getPossibleRegions()
	{
		return $this->supportsRegions;
	}

	public function isRegionSupports()
	{
		return in_array($this->region->getName(), $this->supportsRegions);
	}

	protected function isApiSupportsRegion()
	{
		if (!$this->isRegionSupports()) {
			throw new \Exception('API doesn\'t support region');
		}

		return true;
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
		return new ApiRequest(
			$dryUrl,
			$this->region,
			$this->apiVer,
			$this->region->getEndPoint()->isGlobal()
		);
	}

}
