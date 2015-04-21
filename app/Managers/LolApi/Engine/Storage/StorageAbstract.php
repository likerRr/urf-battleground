<?php namespace URFBattleground\Managers\LolApi\Engine\Storage;

abstract class StorageAbstract implements StorageInterface {

	use StorageInjectableTrait;

	public function __construct($instance = null)
	{
		if ($instance instanceof StorageInterface) {
			$this->injectInstance($instance);
		}
	}

	/**
	 * Check if key exists in storage
	 * @param $key
	 * @return mixed
	 * @throws
	 */
	abstract public function has($key);

	/**
	 * Get key from storage
	 * @param $key
	 * @param null $default
	 * @return mixed
	 * @throws \Exception
	 */
	abstract public function get($key, $default = null);

	/**
	 * Delete key from storage
	 * @param $key
	 * @return mixed
	 * @throws \Exception
	 */
	abstract public function forget($key);

	/**
	 * Put $key with $value for $minutes
	 * @param $key
	 * @param $value
	 * @param $minutes
	 * @return mixed
	 * @throws \Exception
	 */
	abstract public function put($key, $value, $minutes);
}
