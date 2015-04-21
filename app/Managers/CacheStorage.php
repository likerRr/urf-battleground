<?php namespace URFBattleground\Managers;

use URFBattleground\Managers\LolApi\Engine\Storage\StorageInterface;

class CacheStorage implements StorageInterface {
	/**
	 * Check if key exists in storage
	 * @param $key
	 * @return boolean
	 */
	public function has($key)
	{
		return \Cache::has($key);
	}

	/**
	 * Get key from storage
	 * @param $key
	 * @param null $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return \Cache::get($key, $default);
	}

	/**
	 * Delete key from storage
	 * @param $key
	 * @return boolean
	 */
	public function forget($key)
	{
		return \Cache::forget($key);
	}

	/**
	 * Put $key with $value for $minutes
	 * @param $key
	 * @param $value
	 * @param $minutes
	 * @void
	 */
	public function put($key, $value, $minutes)
	{
		\Cache::put($key, $value, $minutes);
	}

}
