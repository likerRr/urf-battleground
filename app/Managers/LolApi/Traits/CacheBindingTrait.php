<?php namespace URFBattleground\Managers\LolApi\Traits;

trait CacheBindingTrait {

	private $minutes;
	private $getResource = true;
	private $getCached = true;

	public function cache($minutes) {
		if ($minutes <= 0) {
			throw new \Exception('Minutes should has a positive value');
		}
		$this->minutes = $minutes;

		return $this;
	}

	public function notCache() {
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
