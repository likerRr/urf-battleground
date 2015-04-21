<?php  namespace URFBattleground\Managers\LolApi\Api;

use URFBattleground\Managers\Helpers;
use URFBattleground\Managers\LolApi\Api\Request\Request;
use URFBattleground\Managers\LolApi\Engine\Storage\StorageInterface;
use URFBattleground\Managers\LolApi\Exception\ApiNotFoundException;
use URFBattleground\Managers\LolApi\Exception\Response\ApiResponseException;
use URFBattleground\Managers\LolApi\Exception\Response\LimitExceedException;
use URFBattleground\Managers\LolApi\Exception\UnsupportedRegionException;
use URFBattleground\Managers\LolApi\LimitManager;
use URFBattleground\Managers\LolApi\Traits\AutoRepeatingOnLimitTrait;
use URFBattleground\Managers\LolApi\Traits\CacheBindingTrait;
use URFBattleground\Managers\LolApi\Traits\RegionBindingTrait;

abstract class ApiAbstract {

	use RegionBindingTrait;
	use CacheBindingTrait;
	use AutoRepeatingOnLimitTrait;

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

	public function getApiVer()
	{
		return $this->apiVer;
	}

	/**
	 * @param $dryUrl
	 * @return Request
	 */
	protected function initApiRequest($dryUrl) {
		return new Request($dryUrl, $this);
	}

	protected function requestResource(Request $request) {
		$this->lastApiRequest = $request;

		return $request->make();
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
			return $this->requestResource($this->lastApiRequest);
		} catch (LimitExceedException $e) {
			return $this->repeatLastUntilLimitPasses();
//		}
		} catch (ApiResponseException $e) {
			Helpers::logException($e, [
				'from' => 'ApiAbstract::repeatLastUntilLimitPasses',
				'resource' => $e->getResponse()->getResource(),
				'data' => $e->getResponse()->json(),
			]);
//			return $e->getResponse();
			throw $e;
		}
	}

}
