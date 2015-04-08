<?php namespace URFBattleground\Managers\RiotApi\Api;

use URFBattleground\Managers\Helpers;
use URFBattleground\Managers\RiotApi\StaticData\Region;

class ApiRequest {

	private $dryUrl;
	private $preparedUrl;
	private $queryParameters;
	private $pathParameters;
	private $region;
	private $apiVer;

	public function __construct($dryUrl, Region $region, $apiVer)
	{
		$this->region = $region;
		$this->apiVer = $apiVer;
		$this->dryUrl = $dryUrl;
	}

	public function setPathParameters($pathParameters) {
		$this->pathParameters = Helpers::nullOrArray($pathParameters);

		return $this;
	}

	public function setQueryParameters($queryParameters) {
		$this->queryParameters = Helpers::nullOrArray($queryParameters);

		return $this;
	}

}
