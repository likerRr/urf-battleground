<?php namespace URFBattleground\Managers\LolApi\Exception\Response;

class BadRequestException extends ApiResponseException {

	public function __construct($message = 'Bad request', $code = 400, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}
