<?php namespace URFBattleground\Managers\RiotApi;

use Illuminate\Support\Facades\Facade;

class RiotApiFacade extends Facade {

	protected static function getFacadeAccessor()
	{
		return 'riotapi';
	}

}