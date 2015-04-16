<?php namespace URFBattleground\Managers\LolApi\Exception\Response;

use URFBattleground\Managers\LolApi\Api\Response\Response;

class UnauthorizedException extends ApiResponseException {

	public function __construct(Response $response, $message = 'Unauthorized', $code = 401, \Exception $previous = null)
	{
		parent::__construct($response, $message, $code, $previous);
	}

}
