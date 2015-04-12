<?php  namespace URFBattleground\Managers\LolApi\Api;

use URFBattleground\Managers\LolApi\Api\Request\Request;
use URFBattleground\Managers\LolApi\Traits\CacheBindingTrait;
use URFBattleground\Managers\LolApi\Traits\RegionBindingTrait;

abstract class ApiAbstract {

	use RegionBindingTrait;
	use CacheBindingTrait;

	protected $apiVer;
	protected $dryUrl;
	private $lastApiRequest;

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
	 * @return Request
	 */
	protected function initApiRequest($dryUrl) {
		$request = new Request(
			$dryUrl,
			$this->region,
			$this->apiVer,
			$this->region->getEndPoint()->isGlobal()
		);

		$this->lastApiRequest = $request;

		return $this->lastApiRequest;
	}

	public function requestResource(Request $request) {
		return $request->make($this->storeTime());
	}

	public function repeatLastRequest() {
		if (!$this->lastApiRequest instanceof Request) {
			throw new \Exception('No any API has been executed');
		}

		return $this->requestResource($this->lastApiRequest);
	}

}
