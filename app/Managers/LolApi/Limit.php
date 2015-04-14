<?php namespace URFBattleground\Managers\LolApi;

class Limit {

	private $beats;
	private $seconds;
	private $actual;

	public function __construct($beats, $seconds)
	{
		$this->beats = $beats;
		$this->seconds = $seconds;
	}

	/**
	 * @param boolean $actual
	 * @return $this
	 */
	public function setActual($actual)
	{
		$this->actual = (bool) $actual;

		return $this;
	}

	public function isActual()
	{
		return $this->actual;
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
