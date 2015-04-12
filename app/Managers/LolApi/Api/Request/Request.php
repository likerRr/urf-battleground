<?php namespace URFBattleground\Managers\LolApi\Api\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use URFBattleground\Managers\Helpers;
use URFBattleground\Managers\LolApi\Api\Response\CachedResponse;
use URFBattleground\Managers\LolApi\Api\Response\Response;
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
	 * @return Response
	 */
	public function make($storeTime)
	{
		$queryParameters = $this->addApiKeyToQuery();
		$key = $this->getResource();
		$cachedResponse = new CachedResponse($key);

		try {
			if ($cachedResponse->isCached()) {
				$response = $cachedResponse;
			} else {
				$response = $this->client->get(null, [
					'query' => $queryParameters
				]);
			}
			$apiResponse = new Response($response, $storeTime);
		} catch (ClientException $e) {
			Helpers::logException($e, $this->handleClientException($e));
			$apiResponse = new Response($e);
		}

		return $apiResponse;
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
