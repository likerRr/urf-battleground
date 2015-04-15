<?php namespace URFBattleground\Managers\LolApi\Exception\Response;

class LimitExceedException extends ApiResponseException {

	public function __construct($message = 'Limit Exceed', $code = 429, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}
