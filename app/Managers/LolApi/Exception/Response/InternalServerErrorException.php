<?php namespace URFBattleground\Managers\LolApi\Exception\Response;

use URFBattleground\Managers\LolApi\Api\Response\Response;

class InternalServerErrorException extends ApiResponseException {

	public function __construct(Response $response, $message = 'Internal server error', $code = 500, \Exception $previous = null)
	{
		parent::__construct($response, $message, $code, $previous);
	}

}
