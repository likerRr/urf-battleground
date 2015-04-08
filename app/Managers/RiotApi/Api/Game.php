<?php namespace URFBattleground\Managers\RiotApi\Api;

class Game extends Api {

	protected $apiVersion = '4.1';
	protected $url = '/api/lol/{region}/{version}/game/ids';
	protected $region;

	protected function compileUrl() {

	}

}