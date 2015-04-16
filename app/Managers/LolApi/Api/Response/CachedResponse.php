<?php namespace URFBattleground\Managers\LolApi\Api\Response;

class CachedResponse {

	private $key;
	private $cachedResponse;
	private $isInCache;

	public function __construct($key)
	{
		$this->key = $key;
		$this->isInCache = \Cache::has($key);
		$this->cachedResponse = \Cache::get($key, []);
	}

	public function forget()
	{
		if ($this->isCached()) {
			\Cache::forget($this->key);
		}
	}

	public function isCached() {
		return $this->isInCache;
	}

	public function put($data, $minutes) {
		if ($minutes <= -1) {
			\Cache::forget($this->key);
		} else {
			$cachedResponse = [
				'data' => $data['data'],
				'code' => $data['code'],
				'resource' => $data['resource'],
			];
			\Cache::put($this->key, $data, $minutes);
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


}
