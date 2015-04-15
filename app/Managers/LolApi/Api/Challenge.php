<?php namespace URFBattleground\Managers\LolApi\Api;

use URFBattleground\Managers\LolApi\Region;

class Challenge extends ApiAbstract {

	protected $apiVer = '4.1';

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
		Region::TR,
	];

	public function gameIds($beginDate) {
//		$this->region->getEndPoint()->setGlobal();
		$this->isApiSupportsRegion();
		$request = $this->initApiRequest('/api/lol/{region}/{apiVer}/game/ids')->setQueryParameters([
			'beginDate' => $beginDate
		]);

		return $this->requestResource($request);
	}

}
