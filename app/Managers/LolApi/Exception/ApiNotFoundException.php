<?php namespace URFBattleground\Managers\LolApi\Exception;

class ApiNotFoundException extends LolApiGeneralException {

	public function __construct($message = 'API not found', $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}
