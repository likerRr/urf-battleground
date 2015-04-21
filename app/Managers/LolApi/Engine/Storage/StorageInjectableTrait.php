<?php namespace URFBattleground\Managers\LolApi\Engine\Storage;

use URFBattleground\Managers\LolApi\Engine\Core\InjectableTrait;
use URFBattleground\Managers\LolApi\Engine\Exception\InvalidStorageInstanceException;

trait StorageInjectableTrait {

	use InjectableTrait {
		injectInstance as inject;
		instance as parentInstance;
	}

	/**
	 * @param $instance
	 * @return $this
	 */
	final public function injectInstance($instance) {
		if ($instance instanceof StorageInterface) {
			$this->inject($instance);
		}

		return $this;
	}

	/**
	 * @return StorageInterface
	 * @throws InvalidStorageInstanceException
	 */
	final public function instance()
	{
		if (!$this->parentInstance() instanceof StorageInterface) {
			throw new InvalidStorageInstanceException();
		}

		return $this->parentInstance();
	}

}
