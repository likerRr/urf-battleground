<?php namespace URFBattleground\Managers\RiotApi\Contracts;

use URFBattleground\Managers\RiotApi\Api\Game;

interface RiotApi {

	/**
	 * @return Game
	 */
	public function game();

	/**
	 * @param $region
	 * @return mixed
	 */
	public function setRegion($region);

	/**
	 * @return string
	 */
	public function getRegion();

}