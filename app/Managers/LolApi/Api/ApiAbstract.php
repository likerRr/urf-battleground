<?php  namespace URFBattleground\Managers\LolApi\Api;

use URFBattleground\Managers\LolApi\Region;
use URFBattleground\Managers\LolApi\Traits\CacheBindingTrait;
use URFBattleground\Managers\LolApi\Traits\RegionBindingTrait;

abstract class ApiAbstract {

	use RegionBindingTrait;
	use CacheBindingTrait;

	protected $apiVer;
	protected $dryUrl;

	protected $supportsRegions = [];

	public function getPossibleRegions()
	{
		return $this->supportsRegions;
	}

	public function isRegionSupports()
	{
		return in_array($this->region->getName(), $this->supportsRegions);
	}

	public function isApiSupportsRegion()
	{
		if (!$this->isRegionSupports()) {
			throw new \Exception('API doesn\'t support region');
		}

		return true;
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
