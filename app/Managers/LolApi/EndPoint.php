<?php namespace URFBattleground\Managers\LolApi;

class EndPoint {

	private $region;
	private $platformId;
	private $host;
	private $title;
	private $isGlobal = false;
	private $global = 'https://global.api.pvp.net';

	public function __construct($region, $platformId, $host, $title)
	{
		$this->region = $region;
		$this->platformId = $platformId;
		$this->host = $host;
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getRegion()
	{
		return $this->region;
	}

	/**
	 * @return mixed
	 */
	public function getPlatformId()
	{
		return $this->platformId;
	}

	/**
	 * @return mixed
	 */
	public function getHost()
	{
		if ($this->isGlobal()) {
			return $this->global;
		}
		return $this->host;
	}

	/**
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	public function setGlobal() {
		$this->isGlobal = true;

		return $this;
	}

	public function isGlobal() {
		return $this->isGlobal;
	}

}