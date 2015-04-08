<?php namespace URFBattleground\Managers\RiotApi\Contracts;

use URFBattleground\Managers\RiotApi\Api\ApiChallenge;

interface RiotApi {

	/**
	 * @return ApiChallenge
	 */
	public function apiChallenge();

	/**
	 * @param $region
	 * @return mixed
	 */
	public function setGlobalRegion($region);

	/**
	 * @return string
	 */
	public function getGlobalRegion();

}
