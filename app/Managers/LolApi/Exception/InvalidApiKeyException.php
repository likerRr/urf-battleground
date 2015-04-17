<?php namespace URFBattleground\Managers\LolApi\Exception;

class InvalidApiKeyException extends LolApiGeneralException {

	public function __construct($message = 'Invalid API key', $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}
