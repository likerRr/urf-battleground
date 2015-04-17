<?php namespace URFBattleground\Managers\LolApi\Exception;

class UnexpectedException extends LolApiGeneralException {

	public function __construct($message = 'Unexpected exception', $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}
