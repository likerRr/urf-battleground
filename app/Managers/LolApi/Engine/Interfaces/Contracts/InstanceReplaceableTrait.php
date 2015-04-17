<?php namespace URFBattleground\Managers\LolApi\Engine\Interfaces\Contracts;

trait InstanceReplaceableTrait {

	private $instance;

	protected function injectInstance($instance)
	{
		$this->instance = $instance;
	}

}