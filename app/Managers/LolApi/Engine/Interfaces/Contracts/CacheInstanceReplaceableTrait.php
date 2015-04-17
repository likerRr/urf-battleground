<?php namespace URFBattleground\Managers\LolApi\Engine\Interfaces\Contracts; 

use URFBattleground\Managers\LolApi\Engine\Interfaces\CacheStore;

trait CacheInstanceReplaceableTrait {

	use InstanceReplaceableTrait {
		injectInstance as inject;
	};

	public function injectInstance($instance) {
		if ($instance instanceof CacheStore) {
			$this->inject($instance);
		}
	}

}