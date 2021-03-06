<?php namespace URFBattleground\Managers\LolApi\Traits;

use URFBattleground\Managers\LolApi\Exception\UnexpectedException;

trait CacheBindingTrait {

	private $minutes;
	private $getResource = true;
	private $getCached = true;

	/**
	 * @param $minutes
	 * @return $this
	 * @throws UnexpectedException
	 */
	public function cache($minutes) {
		if ($minutes < -1) {
			throw new UnexpectedException('Minutes should has a positive value');
		}
		$this->minutes = $minutes;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function preventCaching() {
		$this->minutes = -1;

		return $this;
	}

	/**
	 * Time to store response in minutes
	 * @return mixed
	 */
	public function cacheTime() {
		return $this->minutes;
	}

	public function getResource()
	{
		$this->getResource = true;
		$this->getCached = false;

		return $this;
	}

	public function getCached()
	{
		$this->getResource = false;
		$this->getCached = true;

		return $this;
	}

	public function getFromCacheOrResource()
	{
		$this->getResource = true;
		$this->getCached = true;

		return $this;
	}

	public function isGetFromResource()
	{
		return $this->getResource;
	}

	public function isGetFromCache()
	{
		return $this->getCached;
	}

}
