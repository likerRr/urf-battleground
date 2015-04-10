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
		Region::PBE,
	];

	public function gameIds($beginDate) {
//		$this->region->getEndPoint()->setGlobal();
		$request = $this->initApiRequest('/api/lol/{region}/{apiVer}/game/ids')->setQueryParameters([
			'beginDate' => $beginDate
		]);

		return $this->requestResource($request);
	}

	public function requestResource(ApiRequest $request) {
		$key = $request->getResource();
		if (\Cache::has($key)) {
			$response = \Cache::get($key);
		} else {
			$response = $request->make();

			if ($response->isOk() && $this->liveTimeMinutes()) {
				\Cache::put($key, $response, ($this->liveTimeMinutes()));
			}
		}

		return $response;
	}

}
