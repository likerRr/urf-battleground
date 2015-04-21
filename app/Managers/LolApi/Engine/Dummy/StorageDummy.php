<?php namespace URFBattleground\Managers\LolApi\Engine\Dummy;

use URFBattleground\Managers\LolApi\Engine\Storage\StorageInterface;

class StorageDummy implements StorageInterface {
	use DummyCallableTrait;
	/**
	 * Check if key exists in storage
	 * @param $key
	 * @return boolean
	 */
	public function has($key)
	{
		return false;
	}

	/**
	 * Get key from storage
	 * @param $key
	 * @param null $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return $default;
	}

	/**
	 * Delete key from storage
	 * @param $key
	 * @return boolean
	 */
	public function forget($key)
	{
		return true;
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

	}
}
