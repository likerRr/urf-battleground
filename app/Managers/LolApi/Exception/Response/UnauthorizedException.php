<?php namespace URFBattleground\Managers\LolApi\Exception\Response;

class UnauthorizedException extends ApiResponseException {

	public function __construct($message = 'Unauthorized', $code = 401, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}
