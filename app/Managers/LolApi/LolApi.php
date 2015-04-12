<?php namespace URFBattleground\Managers\LolApi;

use URFBattleground\Managers\LolApi\Api\ApiAbstract;
use URFBattleground\Managers\LolApi\Traits\CacheBindingTrait;
use URFBattleground\Managers\LolApi\Traits\RegionBindingTrait;

class LolApi {

	use RegionBindingTrait;
	use CacheBindingTrait;

	private static $apiKey;

	public function __construct()
	{
		self::$apiKey = \Config::get('lolapi.apiKey');
	}

	public static function getApiKey()
	{
		return self::$apiKey;
	}

	/**
	 * @return Api\Challenge
	 */
	public function apiChallenge()
	{
		return $this->initApi(new Api\Challenge());
	}

	public function initApi(ApiAbstract $apiAbstract)
	{
		$apiAbstract
			->setRegion($this->getRegion())
			->isApiSupportsRegion();
		$apiAbstract
			->store($this->storeTime());

		return $apiAbstract;
	}

}
