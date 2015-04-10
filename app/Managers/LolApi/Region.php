<?php namespace URFBattleground\Managers\LolApi;

class Region {

	private $name;
	/** @var \URFBattleground\Managers\LolApi\EndPoint */
	private $endPoint;

	const BR = 'br';
	const EUNE = 'eune';
	const EUW = 'euw';
	const KR = 'kr';
	const LAN = 'lan';
	const LAS = 'las';
	const NA = 'na';
	const OCE = 'oce';
	const TR = 'tr';
	const RU = 'ru';
	const PBE = 'pbe';

	private static $regionsData = [
		self::BR => [
			'platformId' => 'BR1',
			'host' => 'https://br.api.pvp.net',
			'title' => 'Brazil',
		],
		self::EUNE => [
			'platformId' => 'EUN1',
			'host' => 'https://eune.api.pvp.net',
			'title' => 'EU Nordic & East',
		],
		self::EUW => [
			'platformId' => 'EUW1',
			'host' => 'https://euw.api.pvp.net',
			'title' => 'EU West',
		],
		self::KR => [
			'platformId' => 'KR',
			'host' => 'https://kr.api.pvp.net',
			'title' => 'Korea',
		],
		self::LAN => [
			'platformId' => 'LA1',
			'host' => 'https://lan.api.pvp.net',
			'title' => 'Latin America North',
		],
		self::LAS => [
			'platformId' => 'LA2',
			'host' => 'https://las.api.pvp.net',
			'title' => 'Latin America South',
		],
		self::NA => [
			'platformId' => 'NA1',
			'host' => 'https://na.api.pvp.net',
			'title' => 'North America',
		],
		self::OCE => [
			'platformId' => 'OC1',
			'host' => 'https://oce.api.pvp.net',
			'title' => 'Oceania',
		],
		self::TR => [
			'platformId' => 'TR1',
			'host' => 'https://tr.api.pvp.net',
			'title' => 'Turkey',
		],
		self::RU => [
			'platformId' => 'RU',
			'host' => 'https://ru.api.pvp.net',
			'title' => 'Russia',
		],
		self::PBE => [
			'platformId' => 'PBE',
			'host' => 'https://pbe.api.pvp.net',
			'title' => 'Public Beta Environment',
		]
	];

	public function __construct($name)
	{
		$this->name = $name;
		$this->endPoint = $this->initEndPoint($name);
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return \URFBattleground\Managers\LolApi\EndPoint
	 */
	public function getEndPoint()
	{
		return $this->endPoint;
	}

	/**
	 * @return array Returns all playable regions
	 */
	public static function all()
	{
		return array_keys(self::$regionsData);
	}

	/**
	 * Returns all regions except $regions
	 * @param $regions
	 * @return array
	 */
	public static function except($regions)
	{
		if (is_string($regions)) {
			$regions = (array)$regions;
		}

		return array_diff(self::all(), $regions);
	}

	private function initEndPoint($regionName)
	{
		$desc = array_get(self::$regionsData, $regionName);

		return new EndPoint($regionName, $desc['platformId'], $desc['host'], $desc['title']);
	}

}