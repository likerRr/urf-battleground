<?php namespace URFBattleground\Managers\LolApi\Traits;

trait CacheBindingTrait {

	private $seconds;
	private $minutes;

	public function store($minutes) {
		$this->seconds = $minutes * 60;
		$this->minutes = $minutes;

		return $this;
	}

	public function liveTimeMinutes() {
		return $this->minutes;
	}

	public function liveTimeSeconds() {
		return $this->seconds;
	}

}
