<?php  namespace URFBattleground\Managers\LolApi\Api;

use URFBattleground\Managers\LolApi\Api\Request\Request;
use URFBattleground\Managers\LolApi\Exception\ApiNotFoundException;
use URFBattleground\Managers\LolApi\Exception\Response\ApiResponseException;
use URFBattleground\Managers\LolApi\Exception\UnsupportedRegionException;
use URFBattleground\Managers\LolApi\LimitManager;
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
			throw new UnsupportedRegionException;
		}

		return true;
	}

	/**
	 * @param $dryUrl
	 * @return Request
	 */
	protected function initApiRequest($dryUrl) {
		return new Request(
			$dryUrl,
			$this->region,
			$this->apiVer,
			$this->region->getEndPoint()->isGlobal()
		);
	}

	public function requestResource(Request $request) {
		$this->lastApiRequest = $request;

		return $request->make($this->storeTime());
	}

	public function repeatLast() {
		if (!$this->lastApiRequest instanceof Request) {
			throw new ApiNotFoundException;
		}

		return $this->requestResource($this->lastApiRequest);
	}

	public function repeatLastUntilSuccess() {
		if (!$this->lastApiRequest instanceof Request) {
			throw new ApiNotFoundException;
		}

		try {
			$sleepTime = LimitManager::nearestAvailableAfterSeconds();
//			var_dump($sleepTime);die;
			if ($sleepTime > 0) {
				sleep($sleepTime);
			}

			return $this->requestResource($this->lastApiRequest);
		} catch (ApiResponseException $e) {
			$this->repeatLastUntilSuccess();
		}

		return false;
	}

}
