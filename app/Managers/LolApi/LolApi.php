<?php namespace URFBattleground\Managers\LolApi;

use URFBattleground\Managers\LolApi\Api\ApiAbstract;
use URFBattleground\Managers\LolApi\Traits\CacheBindingTrait;
use URFBattleground\Managers\LolApi\Traits\RegionBindingTrait;

class LolApi {

	use RegionBindingTrait;
	use CacheBindingTrait;

	private static $apiKey;
	private static $readyAfter;
	private static $readyAt;

	public function __construct()
	{
		self::$apiKey = \Config::get('lolapi.apiKey');
//		$limits = \Config::get('lolapi.limits');
//		LimitManager::init($limits);
	}

	public static function setReadyAfter($seconds)
	{
		self::$readyAfter = $seconds;
		self::$readyAt = time() + self::$readyAfter;
	}

	public static function getReadyAfter()
	{
		return self::$readyAfter;
	}

	public static function isReady()
	{
		return (time() >= (int)self::$readyAt);
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
			->setRegion($this->getRegion());
		$apiAbstract
			->cache($this->cacheTime());

		return $apiAbstract;
	}

}
