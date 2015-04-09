<?php namespace URFBattleground\Managers\RiotApi\Api;

use URFBattleground\Managers\RiotApi\StaticData\Region;

class ApiChallenge extends ApiAbstract {

	protected $apiVer = '4.1';
	protected $region;

	protected $supportsRegions = [
		Region::BR,
		Region::EUNE,
		Region::EUW,
		Region::KR,
		Region::LAN,
		Region::LAS,
		Region::NA,
		Region::OCE,
		Region::RU,
		Region::TR
	];

	public function gameIds($beginDate) {
		$request = $this->initApiRequest('/api/lol/{region}/{apiVer}/game/ids')->setQueryParameters([
			'beginDate' => $beginDate
		])->make();
	}

}
