<?php namespace URFBattleground\Managers\LolApi\Api\Response;

class CachedResponse {

	private $key;
	private $cachedResponse;
	private $inCache;

	public function __construct($key)
	{
		$this->key = $key;
		$this->inCache = \Cache::has($key);
		$this->cachedResponse = \Cache::get($key, []);
	}

	public function isCached() {
		return $this->inCache;
	}

	public function put($data, $minutes) {
		$cachedResponse = [
			'data' => $data['data'],
			'code' => $data['code'],
			'resource' => $data['resource'],
		];
		\Cache::put($this->key, $data, $minutes);
		$this->cachedResponse = $cachedResponse;

		return $this;
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


}
