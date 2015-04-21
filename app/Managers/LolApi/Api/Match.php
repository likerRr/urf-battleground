<?php namespace URFBattleground\Managers\LolApi\Api;

use URFBattleground\Managers\LolApi\Api\Response\Dto\ListDto;
use URFBattleground\Managers\LolApi\Api\Response\Dto\MatchDetailDto;
use URFBattleground\Managers\LolApi\Region;

class Match extends ApiAbstract {

	protected $apiVer = '2.2';
	private $request;

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

	/**
	 * @param $matchId
	 * @param bool $includeTimeline
	 * @return Response\Response
	 */
	public function byId($matchId, $includeTimeline = false) {
		$this->before();

		$this->request = $this
			->initApiRequest('/api/lol/{region}/v{apiVer}/match/{matchId}')
			->setPathParameters([
				'matchId' => $matchId
			])
			->setQueryParameters([
				'includeTimeline' => (int) (bool) ($includeTimeline)
			]);

		return new MatchDetailDto($this->after());
//		return new ListDto($this->after());
	}

	private function before() {
		$this->isApiSupportsRegion();
	}

	private function after() {
		$request = $this->request;
		$this->request = null;

		return $this->requestResource($request);
	}

}
