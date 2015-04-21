<?php namespace URFBattleground\Managers\LolApi\Engine\Storage;

interface StorageInterface {

	/**
	 * Check if key exists in storage
	 * @param $key
	 * @return boolean
	 */
	public function has($key);

	/**
	 * Get key from storage
	 * @param $key
	 * @param null $default
	 * @return mixed
	 */
	public function get($key, $default = null);

	/**
	 * Delete key from storage
	 * @param $key
	 * @return boolean
	 */
	public function forget($key);

	/**
	 * Put $key with $value for $minutes
	 * @param $key
	 * @param $value
	 * @param $minutes
	 * @void
	 */
	public function put($key, $value, $minutes);

}
