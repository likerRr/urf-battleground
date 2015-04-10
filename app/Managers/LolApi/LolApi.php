<?php namespace URFBattleground\Managers\LolApi;

class LolApi implements LolApiContract {

	/** @var  Region */
	private $region;
	private static $apiKey;

	public function __construct()
	{
		self::$apiKey = \Config::get('lolapi.apiKey');
	}

	public static function getApiKey()
	{
		return self::$apiKey;
	}

	/**
	 * @return Api\Challenge
	 */
	public function apiChallenge()
	{
		return new Api\Challenge($this->getRegion());
	}

	public function setRegion($region)
	{
		$this->region = new Region($region);

		return $this;
	}

	public function getRegion()
	{
		return $this->region;
	}

}
