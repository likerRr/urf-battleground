<?php  namespace URFBattleground\Managers\LolApi\Api;

use URFBattleground\Managers\LolApi\Api\Request\Request;
use URFBattleground\Managers\LolApi\Exception\ApiNotFoundException;
use URFBattleground\Managers\LolApi\Exception\Response\ApiResponseException;
use URFBattleground\Managers\LolApi\Exception\Response\LimitExceedException;
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

	protected function requestResource(Request $request) {
		$this->lastApiRequest = $request;

		return $request->make($this->cacheTime(), $this->isGetFromCache(), $this->isGetFromResource());
	}

	public function repeatLast() {
		if (!$this->lastApiRequest instanceof Request) {
			throw new ApiNotFoundException;
		}

		return $this->requestResource($this->lastApiRequest);
	}

	public function repeatLastUntilLimitPasses() {
		if (!$this->lastApiRequest instanceof Request) {
			throw new ApiNotFoundException;
		}

		try {
			if (!\LolApi::isReady()) {
				sleep(\LolApi::getReadyAfter());
			}

			return $this->requestResource($this->lastApiRequest);
		} catch (LimitExceedException $e) {
			return $this->repeatLastUntilLimitPasses();
		} catch (ApiResponseException $e) {
			return $e->getResponse();
		}
	}

}
