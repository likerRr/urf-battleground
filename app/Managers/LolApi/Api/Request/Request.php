<?php namespace URFBattleground\Managers\LolApi\Api\Request;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use URFBattleground\Managers\Helpers;
use URFBattleground\Managers\LolApi\Api\ApiAbstract;
use URFBattleground\Managers\LolApi\Api\Response\ResponseCached;
use URFBattleground\Managers\LolApi\Api\Response\Response;
use URFBattleground\Managers\LolApi\Exception\Response\LimitExceedException;
use URFBattleground\Managers\LolApi\Exception\Response\NotFoundInCacheException;
use URFBattleground\Managers\LolApi\Exception\UnexpectedException;
use URFBattleground\Managers\LolApi\Region;

class Request
{

	private $apiDryPath;
	private $queryParameters = [];
	private $pathParameters = [];
	private $apiKey = '';
	/** @var  ApiAbstract */
	private $apiObject;

	/** @var Client */
	private $client;
	private $externalRequestsCount = 0;
	private $cachedRequestsCount = 0;
	private $limitRequestsCount;

	/**
	 * @param $apiDryPath
	 * @param ApiAbstract $apiObject
	 */
	public function __construct($apiDryPath, ApiAbstract $apiObject)
	{
		$this->apiObject = $apiObject;
		$this->apiDryPath = $apiDryPath;
		$this->apiKey = \LolApi::getApiKey();
//		$this->client = new Client([
//			'base_url' => $this->getBaseUrl()
//		]);
		$this->setPathParameters([
			'region' => $this->apiObject->getRegion()->getName(),
			'apiVer' => $this->apiObject->getApiVer()
		]);
	}

	public function setPathParameters($pathParameters)
	{
		$this->pathParameters = array_merge($this->pathParameters, Helpers::nullOrArray($pathParameters));

		return $this;
	}

	public function setQueryParameters($queryParameters)
	{
		$this->queryParameters = array_merge($this->queryParameters, Helpers::nullOrArray($queryParameters));

		return $this;
	}

	private function getHost()
	{
		return $this->apiObject->getRegion()->getEndPoint()->getHost();
	}

	private function getResourceUrl() {
		return $this->client->getBaseUrl() . '?' . http_build_query($this->addApiKeyToQuery());
	}

	private function waitForReady()
	{
		if (!\LolApi::isReady()) {
			sleep(\LolApi::getReadyAfter());
		}
	}

	private function initClient()
	{
		$this->client = new Client([
			'base_url' => [$this->getHost() . $this->apiDryPath, $this->pathParameters],
			'defaults' => [
				'query' => $this->queryParameters
			]
		]);

		return $this->client;
	}

	/**
	 * @return Response
	 * @throws LimitExceedException
	 * @throws UnexpectedException
	 * @throws \Exception
	 */
	public function make()
	{
		$this->initClient();
		$apiObject = $this->apiObject;
		$isGetCached = $apiObject->isGetFromCache();
		$isGetResource = $apiObject->isGetFromResource();
		$this->waitForReady();
		$key = $this->getResourceUrl();

		try {
			if ($isGetCached && $isGetResource) {
				$response = $this->makeCachedRequest($key);
				if (!$response) {
					$response = $this->makeResourceRequest();
				}
			} elseif ($isGetCached) {
				$response = $this->makeCachedRequest($key, true);
			} else {
				$response = $this->makeResourceRequest();
			}
			// handles only code 200 (OK) responses
			$apiResponse = new Response($response, $apiObject->cacheTime());
		} catch (ClientException $e) {
			// handles 4XX codes
			try {
				$apiResponse = new Response($e);
			} catch (LimitExceedException $e) {
				// hook for limit exceed
				$autoRepeatResult = $this->handleAutoRepeat();
				if ($autoRepeatResult) {
					return $autoRepeatResult;
				}
				// not auto-repeat or repeat times ended
				throw $e;
			}
		} catch (\Exception $e) {
			throw new UnexpectedException($e->getMessage(), $e->getCode(), $e);
		}

		return $apiResponse;
	}

	/**
	 * @return bool|Response
	 * @throws LimitExceedException
	 * @throws UnexpectedException
	 * @throws \Exception
	 */
	private function handleAutoRepeat()
	{
		$apiObject = $this->apiObject;

		if ($apiObject->isAutoRepeat()) {
			if ($apiObject->isRepeatUntilNotPass()) {
				return $this->make();
			} else {
				if (empty($this->limitRequestsCount)) {
					$this->limitRequestsCount = $apiObject->getRepeatAttempts();
				}
				if ($this->limitRequestsCount > 0) {
					$this->limitRequestsCount--;
					return $this->make();
				}
			}
		}

		return false;
	}

	private function makeCachedRequest($key, $throwIfNotFound = false)
	{
		$this->cachedRequestsCount++;
		$cachedResponse = new ResponseCached($key);
		if ($cachedResponse->isCached()) {
			return $cachedResponse;
		}

		if ($throwIfNotFound) {
			throw new NotFoundInCacheException();
		}

		return false;
	}

	private function makeResourceRequest()
	{
		$this->externalRequestsCount++;
		$queryParameters = $this->addApiKeyToQuery();

		return $this->client->get([$this->apiDryPath, $this->pathParameters], [
			'query' => $queryParameters,
		]);
	}

	private function addApiKeyToQuery()
	{
		return array_add($this->queryParameters, 'api_key', $this->apiKey);
	}

}
