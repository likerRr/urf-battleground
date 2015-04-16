<?php namespace URFBattleground\Managers\LolApi\Api\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use URFBattleground\Managers\Helpers;
use URFBattleground\Managers\LolApi\Api\Response\CachedResponse;
use URFBattleground\Managers\LolApi\Api\Response\Response;
use URFBattleground\Managers\LolApi\Exception\Response\NotFoundInCacheException;
use URFBattleground\Managers\LolApi\Region;

class Request
{

	private $dryUrl;
	private $isGlobalEP = false;
	private $queryParameters = [];
	private $pathParameters = [];
	private $apiKey = '';
	private $apiVer = '';

	/** @var Client */
	private $client;
	/** @var  Region */
	private $region;

	public function __construct($dryUrl, Region $region, $apiVer, $isGlobalEP)
	{
		$this->region = $region;
		$this->apiVer = $apiVer;
		$this->dryUrl = $dryUrl;
		$this->isGlobalEP = $isGlobalEP;
		$this->apiKey = \LolApi::getApiKey();
		$this->client = new Client([
			'base_url' => [
				$this->getBaseUrl(), [
					'region' => $this->region->getName(),
					'apiVer' => 'v' . $this->apiVer
				]
			]
		]);
	}

	public function setPathParameters($pathParameters)
	{
		$this->pathParameters = Helpers::nullOrArray($pathParameters);

		return $this;
	}

	public function setQueryParameters($queryParameters)
	{
		$this->queryParameters = Helpers::nullOrArray($queryParameters);

		return $this;
	}

	private function getBaseUrl()
	{
		return $this->region->getEndPoint()->getHost() . $this->dryUrl;
	}

	public function getResource() {
		return $this->client->getBaseUrl() . '?' . http_build_query($this->addApiKeyToQuery());
	}

	/**
	 * @param $storeTime
	 * @param bool $isGetCached
	 * @param $isGetResource
	 * @return Response
	 * @throws NotFoundInCacheException
	 */
	public function make($storeTime, $isGetCached = true, $isGetResource = true)
	{
		$key = $this->getResource();
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
			$apiResponse = new Response($response, $storeTime);
		} catch (ClientException $e) {
			Helpers::logException($e, $this->handleClientException($e));
			$apiResponse = new Response($e);
		}

		return $apiResponse;
	}

	private function makeCachedRequest($key, $throwIfNotFound = false)
	{
		$cachedResponse = new CachedResponse($key);
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
		$queryParameters = $this->addApiKeyToQuery();

		return $this->client->get(null, [
			'query' => $queryParameters
		]);
	}

	private function handleClientException(ClientException $e)
	{
		return [
			'base_url' => $this->client->getBaseUrl(),
			'query' => $e->getRequest()->getQuery(),
			'body' => $e->getResponse()->json()
		];
	}

	private function addApiKeyToQuery()
	{
		return array_add($this->queryParameters, 'api_key', $this->apiKey);
	}

}
