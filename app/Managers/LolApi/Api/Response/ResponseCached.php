<?php namespace URFBattleground\Managers\LolApi\Api\Response;

class ResponseCached {

	private $key;
	private $cachedResponse;
	private $isInCache;
	private static $prefix = 'lolengine';

	public function __construct($key)
	{
		$this->storage = \LolApi::getStorage();
		$this->key = self::makeKey($key);
		$this->isInCache = $this->storage->has($key);
		$this->cachedResponse = $this->storage->get($key, []);
	}

	public static function makeKey($key)
	{
		return self::$prefix.':'.md5('respkey_' . $key);
	}

	public function forget()
	{
		if ($this->isCached()) {
			$this->storage->forget($this->key);
		}
	}

	public function isCached() {
		return $this->isInCache;
	}

	public function put($data, $minutes) {
		if ($minutes <= -1) {
			$this->storage->forget($this->key);
		} else {
			$cachedResponse = [
				'data' => $data['data'],
				'dataObj' => $data['dataObj'],
				'code' => $data['code'],
				'resource' => $data['resource'],
			];
			$this->storage->put($this->key, $data, $minutes);
			$this->cachedResponse = $cachedResponse;
		}

		return $this;
	}

	public function getResponseData()
	{
		return $this->cachedResponse;
	}

	/**
	 * @return mixed
	 */
	public function getEffectiveUrl()
	{
		return array_get($this->cachedResponse, 'resource');
	}

	/**
	 * @return mixed
	 */
	public function getCode()
	{
		return array_get($this->cachedResponse, 'code');
	}

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return array_get($this->cachedResponse, 'data');
	}

	/**
	 * @return mixed
	 */
	public function getDataObj()
	{
		return array_get($this->cachedResponse, 'dataObj');
	}
}
