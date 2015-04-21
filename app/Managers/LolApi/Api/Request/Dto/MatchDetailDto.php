<?php namespace URFBattleground\Managers\LolApi\Api\Response\Dto;

use URFBattleground\Managers\LolApi\Api\Response\Response;
use URFBattleground\Managers\LolApi\Api\Response\ResponseDto;

class MatchDetailDto extends ResponseDto {

	use DtoUtilityTrait;

	private $mapId;
	private $matchCreation;
	private $matchDuration;
	private $matchId;
	private $matchMode;
	private $matchType;
	private $matchVersion;
	private $participantIdentities = [];
	private $participants = [];
	private $platformId;
	private $queueType;
	private $region;
	private $season;
	private $teams = [];
	private $timeline;

	/**
	 * @param Response $response
	 */
	public function __construct(Response $response)
	{
		parent::__construct($response);
		$this->participantIdentities = new \ArrayIterator();
		$this->participants = new \ArrayIterator();
		$this->teams = new \ArrayIterator();

		$data = $this->response()->getDataObj();
		if (!empty($data)) {
//			dd($data);
			$this->matchId = $this->getVal($data, 'matchId');
			$this->region = $this->getVal($data, 'region');
			$this->platformId = $this->getVal($data, 'platformId');
			$this->matchMode = $this->getVal($data, 'matchMode');
			$this->matchType = $this->getVal($data, 'matchType');
			$this->matchCreation = $this->getVal($data, 'matchCreation');
			$this->matchDuration = $this->getVal($data, 'matchDuration');
			$this->queueType = $this->getVal($data, 'queueType');
			$this->mapId = $this->getVal($data, 'mapId');
			$this->season = $this->getVal($data, 'season');
			$this->matchVersion = $this->getVal($data, 'matchVersion');
//			$this->participants = $data->participants;
			$this->defineParticipantIdentities($this->getVal($data, 'participantIdentities', []));
			var_dump($this);
//			$this->participantIdentities = $data->participantIdentities;
//			$this->teams = $data->teams;
//			$this->timeline = $this->getVal($data, 'timeline');
		}
	}

	private function defineParticipantIdentities($participantIdentities) {
		$this->each($participantIdentities, function($value) {
			$this->participantIdentities->append(new ParticipantIdentityDto($value));
		});
	}

}
