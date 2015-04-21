<?php namespace URFBattleground\Managers\LolApi\Engine\Dummy;

/**
 * Dummy trait to imitate functionality
 * Class DummyCallableTrait
 * @package URFBattleground\Managers\LolApi\Engine\Dummy
 */
trait DummyCallableTrait {

	public function __call($name, $arguments)
	{
		return null;
	}

	public static function __callStatic($name, $arguments)
	{
		return null;
	}

}
