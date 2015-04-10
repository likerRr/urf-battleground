<?php namespace URFBattleground\Managers\LolApi;

use Illuminate\Support\Facades\Facade;

class LolApiFacade extends Facade {

	protected static function getFacadeAccessor()
	{
		return 'URFBattleground\Managers\LolApi\LolApi';
	}

}
