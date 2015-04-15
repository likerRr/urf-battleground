<?php namespace URFBattleground\Managers\LolApi\Exception\Response;

use URFBattleground\Managers\LolApi\Exception\LolApiGeneralException;

class ApiResponseException extends LolApiGeneralException {

	public function __construct($message = '', $code = 429, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}
