<?php namespace URFBattleground\Managers\LolApi\Api;

use URFBattleground\Managers\LolApi\Api\Response\Dto\ListDto;
use URFBattleground\Managers\LolApi\Region;

class Challenge extends ApiAbstract {

	protected $apiVer = '4.1';
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
	 * @param $beginDate
	 * @return ListDto
	 */
	public function gameIds($beginDate) {
		$this->before();
//		$this->getRegion()->getEndPoint()->setGlobal();

		$this->request = $this
			->initApiRequest('/api/lol/{region}/v{apiVer}/game/ids')
			->setQueryParameters([
				'beginDate' => $beginDate
			]);

//		return $this->after();
		return new ListDto($this->after());
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
