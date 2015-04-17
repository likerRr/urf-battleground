<?php namespace URFBattleground\Managers\LolApi;

use URFBattleground\Managers\LolApi\Api\ApiAbstract;
use URFBattleground\Managers\LolApi\Exception\InvalidApiKeyException;
use URFBattleground\Managers\LolApi\Traits\CacheBindingTrait;
use URFBattleground\Managers\LolApi\Traits\RegionBindingTrait;

class LolApi {

	use RegionBindingTrait;
	use CacheBindingTrait;

	private static $apiKey;
	private static $readyAfter;
	private static $readyAt;
	private static $minDelayBeforeRequest;

	public function __construct()
	{
		// TODO make static settings object and move all settings there
		$config = config('lolengine');
		self::setApiKey(array_get($config, 'apiKey'));
		self::setMinDelayBeforeRequest(array_get($config, 'minDelayBeforeRequest', 0));
//		$limits = \Config::get('lolapi.limits');
//		LimitManager::init($limits);
	}

	public static function getMinDelayBeforeRequest()
	{
		return self::$minDelayBeforeRequest;
	}

	public static function setMinDelayBeforeRequest($delay)
	{
		self::$minDelayBeforeRequest = (int) $delay;
	}

	public static function setReadyAfter($seconds)
	{
		self::$readyAfter = (int) $seconds;
		self::$readyAt = time() + self::$readyAfter;
	}

	public static function getReadyAfter()
	{
		return self::$readyAfter;
	}

	public static function isReady()
	{
		$readyAt = self::getReadyAfter() + self::getMinDelayBeforeRequest();

		return (time() >= $readyAt);
	}

	public static function getApiKey()
	{
		return self::$apiKey;
	}

	public static function setApiKey($apiKey)
	{
		if (empty($apiKey)) {
			throw new InvalidApiKeyException('API key can\'t be empty');
		}
		self::$apiKey = $apiKey;
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
