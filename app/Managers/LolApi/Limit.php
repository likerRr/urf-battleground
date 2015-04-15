<?php namespace URFBattleground\Managers\LolApi;

use Carbon\Carbon;
use URFBattleground\Managers\LolApi\Exception\Response\LimitExceedException;

class Limit {

	private $beats;
	private $seconds;
	private $isActive;
	private $key;
	/** @var \Cache */
	private $cache;
	private $resetTimestamp;

	public function __construct($beats, $seconds)
	{
		$this->beats = $beats;
		$this->seconds = $seconds;
		$this->cache = \App::make('cache');
		$this->key = "lolapi_limit_{$seconds}_{$beats}";
	}

	public function tick()
	{
		if ($this->isExceed()) {
			return false;
		}

		return $this->decreaseByOne();
	}

	public function isAvailable()
	{
		return $this->resetTimestamp === null || time() > $this->resetTimestamp;
	}

	public function willResetAfterSeconds()
	{
		$result = $this->resetTimestamp - time();

		return $result >= 1 ? ($result + 1) : 1;
	}

	public function tickOrFail()
	{
		if ($this->isExceed()) {
			throw new LimitExceedException;
		}

		return $this->decreaseByOne();
	}

	private function getLimitCounter()
	{
		return $this->cache->get($this->key);
	}

	public function resetCounter()
	{
		$this->cache->forget($this->key);
	}

	private function decreaseByOne()
	{
		$limitCounter = $this->getLimitCounter();
		if ($limitCounter === null) {
			$this->resetTimestamp = (time() + $this->seconds);
			$this->cache->add($this->key, $this->beats, $this->seconds);
		} else {
			$this->cache->decrement($this->key);
		}

		return $this->getLimitCounter();
	}

	public function isExceed() {
		$limitCounter = $this->getLimitCounter();
		if ($limitCounter === null) {
			return false;
		}

		return ((int) $limitCounter <= 1) ? true : false;
	}

	public function activate()
	{
		$this->isActive = true;

		return $this;
	}

	public function deactivate()
	{
		$this->isActive = false;

		return $this;
	}

	public function isActive()
	{
		return $this->isActive;
	}

	/**
	 * @return mixed
	 */
	public function getSeconds()
	{
		return $this->seconds;
	}

	/**
	 * @return mixed
	 */
	public function getBeats()
	{
		return $this->beats;
	}

}
