<?php namespace URFBattleground\Managers\LolApi\Traits;

trait CacheBindingTrait {

	private $minutes;

	public function store($minutes) {
		$this->minutes = $minutes;

		return $this;
	}

	/**
	 * Time to store response in minutes
	 * @return mixed
	 */
	public function storeTime() {
		return $this->minutes;
	}

}
