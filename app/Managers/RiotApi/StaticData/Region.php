<?php namespace URFBattleground\Managers\RiotApi\StaticData;

class Region
{

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
	const GLOB = 'global';

	private $protocol = 'https';

	private $availableRegions = [
		self::BR => [
			'platformId' => 'BR1',
			'host' => 'br.api.pvp.net',
			'name' => 'Brazil',
		],
		self::EUNE => [
			'platformId' => 'EUN1',
			'host' => 'eune.api.pvp.net',
			'name' => 'EU Nordic & East',
		],
		self::EUW => [
			'platformId' => 'EUW1',
			'host' => 'euw.api.pvp.net',
			'name' => 'EU West',
		],
		self::KR => [
			'platformId' => 'KR',
			'host' => 'kr.api.pvp.net',
			'name' => 'Korea',
		],
		self::LAN => [
			'platformId' => 'LA1',
			'host' => 'lan.api.pvp.net',
			'name' => 'Latin America North',
		],
		self::LAS => [
			'platformId' => 'LA2',
			'host' => 'las.api.pvp.net',
			'name' => 'Latin America South',
		],
		self::NA => [
			'platformId' => 'NA1',
			'host' => 'na.api.pvp.net',
			'name' => 'North America',
		],
		self::OCE => [
			'platformId' => 'OC1',
			'host' => 'oce.api.pvp.net',
			'name' => 'Oceania',
		],
		self::TR => [
			'platformId' => 'TR1',
			'host' => 'tr.api.pvp.net',
			'name' => 'Turkey',
		],
		self::RU => [
			'platformId' => 'RU',
			'host' => 'ru.api.pvp.net',
			'name' => 'Russia',
		],
		self::PBE => [
			'platformId' => 'PBE',
			'host' => 'pbe.api.pvp.net',
			'name' => 'Public Beta Environment',
		],
		self::GLOB => [
			'platformId' => null,
			'host' => 'global.api.pvp.net',
			'name' => 'Global',
		]
	];

	private $regionName;

	public function __construct($region)
	{
		if (!$this->isRegionExists($region)) {
			throw new \Exception('Unsupported region');
		}

		$this->regionName = $region;
	}

	public function isRegionExists($region) {
		return in_array($region, array_keys($this->availableRegions));
	}

	public function getHost()
	{
		return $this->availableRegions[$this->regionName]['host'];
	}

	public function getName()
	{
		return $this->regionName;
	}

	public function getProtocol()
	{
		return $this->protocol . '://';
	}

}
