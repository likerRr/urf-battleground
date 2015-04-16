<?php namespace URFBattleground\Managers\LolApi\Exception\Response;

use URFBattleground\Managers\LolApi\Exception\LolApiGeneralException;

class NotFoundInCacheException extends LolApiGeneralException {

	public function __construct($message = 'Response not found in cache', $code = 404, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}