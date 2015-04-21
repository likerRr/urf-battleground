<?php namespace URFBattleground\Managers\LolApi;

use URFBattleground\Managers\LolApi\Api\ApiAbstract;
use URFBattleground\Managers\LolApi\Engine\Exception\InvalidStorageInstanceException;
use URFBattleground\Managers\LolApi\Engine\Storage\StorageInterface;
use URFBattleground\Managers\LolApi\Engine\Storage\StorageProxy;
use URFBattleground\Managers\LolApi\Engine\Dummy\StorageDummy;
use URFBattleground\Managers\LolApi\Exception\InvalidApiKeyException;
use URFBattleground\Managers\LolApi\Traits\AutoRepeatingOnLimitTrait;
use URFBattleground\Managers\LolApi\Traits\CacheBindingTrait;
use URFBattleground\Managers\LolApi\Traits\RegionBindingTrait;

class LolApi {

	use RegionBindingTrait;
	use CacheBindingTrait;
	use AutoRepeatingOnLimitTrait;

	private static $apiKey;
	private static $readyAfter;
	private static $readyAt;
	private static $minDelayBeforeRequest;
	/** @var StorageInterface */
	private static $storage;

	public function __construct()
	{
		// TODO make static settings object and move all settings there
		$config = config('lolengine');
		self::setApiKey(array_get($config, 'apiKey'));
		self::setMinDelayBeforeRequest(array_get($config, 'minDelayBeforeRequest', 0));
		self::$storage = new StorageProxy(new StorageDummy());
//		$this->cacheInstance->injectInstance(new LolEngineCacheStorage());
//		$limits = \Config::get('lolapi.limits');
//		LimitManager::init($limits);
	}

	/**
	 * Set or get cache instance
	 * @param null $instance
	 * @return $this|StorageInterface
	 * @throws InvalidStorageInstanceException
	 */
	public function storage($instance = null)
	{
		if (!empty($instance)) {
			if (!$instance instanceof StorageInterface) {
				throw new InvalidStorageInstanceException();
			}
			self::$storage->injectInstance($instance);

			return $this;
		}

		return self::$storage;
	}

	/**
	 * @return StorageInterface
	 */
	public static function getStorage()
	{
		return self::$storage;
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

	public static function getReadyAt()
	{
		return self::$readyAt;
	}

	public static function isReady()
	{
		$readyAt = self::getReadyAt() + self::getMinDelayBeforeRequest();

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

	/**
	 * @return Api\Match
	 */
	public function apiMatch()
	{
		return $this->initApi(new Api\Match());
	}

	public function initApi(ApiAbstract $apiAbstract)
	{
		$apiAbstract
			->setRegion($this->getRegion())
			->cache($this->cacheTime());
		if ($this->isAutoRepeat()) {
			$apiAbstract->autoRepeatOnLimitExceed($this->getRepeatAttempts());
		} else {
			$apiAbstract->throwOnLimitExceed();
		}

		return $apiAbstract;
	}

}
