<?php namespace URFBattleground\Managers\LolApi\Engine\Core;

trait InjectableTrait {

	private $instance;

	protected function injectInstance($instance)
	{
		$this->instance = $instance;
	}

	public function instance() {
		return $this->instance;
	}

}
