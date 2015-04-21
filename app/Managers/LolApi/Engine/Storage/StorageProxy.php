<?php namespace URFBattleground\Managers\LolApi\Engine\Storage;

class StorageProxy extends StorageAbstract {

	/**
	 * Check if key exists in storage
	 * @param $key
	 * @return mixed
	 * @throws
	 */
	public function has($key)
	{
		return $this->instance()->has($key);
	}

	/**
	 * Get key from storage
	 * @param $key
	 * @param null $default
	 * @return mixed
	 * @throws \Exception
	 */
	public function get($key, $default = null)
	{
		return $this->instance()->get($key, $default);
	}

	/**
	 * Delete key from storage
	 * @param $key
	 * @return mixed
	 * @throws \Exception
	 */
	public function forget($key)
	{
		return $this->instance()->forget($key);
	}


	/**
	 * Put $key with $value for $minutes
	 * @param $key
	 * @param $value
	 * @param $minutes
	 * @return mixed
	 * @throws \Exception
	 */
	public function put($key, $value, $minutes)
	{
		return $this->instance()->put($key, $value, $minutes);
	}
}
