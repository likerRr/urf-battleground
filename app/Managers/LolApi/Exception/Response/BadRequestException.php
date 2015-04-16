<?php namespace URFBattleground\Managers\LolApi\Exception\Response;

use URFBattleground\Managers\LolApi\Api\Response\Response;

class BadRequestException extends ApiResponseException {

	public function __construct(Response $response, $message = 'Bad request', $code = 400, \Exception $previous = null)
	{
		parent::__construct($response, $message, $code, $previous);
	}

}
