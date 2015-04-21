<?php namespace URFBattleground\Managers\LolApi\Api\Response\Dto;

use URFBattleground\Managers\LolApi\Api\Response\Response;
use URFBattleground\Managers\LolApi\Api\Response\ResponseDto;
use URFBattleground\Managers\LolApi\Engine\Support\Collection;

class MatchDetailDto extends ResponseDto {

//	use DtoUtilityTrait;

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
		$this->participantIdentities = new Collection();
		$this->participants = new Collection();
		$this->teams = new Collection();

		$data = $this->response()->getDataObj();
		if (!empty($data)) {
//			dd($data);
			$this->matchId = getVal($data, 'matchId');
			$this->region = getVal($data, 'region');
			$this->platformId = getVal($data, 'platformId');
			$this->matchMode = getVal($data, 'matchMode');
			$this->matchType = getVal($data, 'matchType');
			$this->matchCreation = getVal($data, 'matchCreation');
			$this->matchDuration = getVal($data, 'matchDuration');
			$this->queueType = getVal($data, 'queueType');
			$this->mapId = getVal($data, 'mapId');
			$this->season = getVal($data, 'season');
			$this->matchVersion = getVal($data, 'matchVersion');
//			$this->participants = $data->participants;
			$this->defineParticipantIdentities(getVal($data, 'participantIdentities', []));
//			var_dump($this);
//			$this->participantIdentities = $data->participantIdentities;
//			$this->teams = $data->teams;
//			$this->timeline = getVal($data, 'timeline');
		}
	}

	private function defineParticipantIdentities($participantIdentities) {
		eachVal($participantIdentities, function($value) {
			$this->participantIdentities->append(new ParticipantIdentityDto($value));
		});
	}

}
