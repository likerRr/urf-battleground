<?php namespace URFBattleground\Managers\RiotApi\Api;

use GuzzleHttp\Client;
use URFBattleground\Managers\Helpers;
use URFBattleground\Managers\RiotApi\StaticData\Region;

class ApiRequest {

	private $dryUrl;
	private $preparedUrl;
	private $queryParameters = [];
	private $pathParameters = [];
	private $apiKey;
	private $client;

	/**
	 * @var Region
	 */
	private $region;
	private $apiVer;

	public function __construct($dryUrl, Region $region, $apiVer, $apiKey)
	{
		$this->region = $region;
		$this->apiVer = $apiVer;
		$this->dryUrl = $dryUrl;
		$this->apiKey = $apiKey;
		$this->client = new Client();
	}

	public function setPathParameters($pathParameters) {
		$this->pathParameters = Helpers::nullOrArray($pathParameters);

		return $this;
	}

	public function setQueryParameters($queryParameters) {
		$this->queryParameters = Helpers::nullOrArray($queryParameters);

		return $this;
	}

	public function make() {
		$uri = $this->buildRequestUri();
		var_dump($uri);
	}

	private function buildRequestUri() {
		$region = $this->region;

		return implode('', [
			$region->getProtocol(),
			$region->getHost(),
			$this->compileUrlParameters(),
		]);
	}

	private function compileUrlParameters() {
		$url = $this->dryUrl;
		$region = $this->region;

		$url = str_replace('{region}', $region->getName(), $url);
		$url = str_replace('{apiVer}', 'v' . $this->apiVer, $url);
		foreach ($this->pathParameters as $key => $val) {
			$url = str_replace('{' . $key . '}', $val, $url);
		}
		$url .= '?api_key=' . $this->apiKey;
		$url .= '&' . http_build_query($this->queryParameters);

		return $url;
	}

}
