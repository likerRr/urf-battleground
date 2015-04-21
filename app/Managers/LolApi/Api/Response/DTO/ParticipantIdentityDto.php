<?php namespace URFBattleground\Managers\LolApi\Api\Response\Dto;

class ParticipantIdentityDto {

	private $participantId;
	// TODO player
	private $player;

	public function __construct($participantIdentity)
	{
		$this->participantId = $participantIdentity->participantId;
		$this->player = object_get($participantIdentity, 'player');
	}


}
