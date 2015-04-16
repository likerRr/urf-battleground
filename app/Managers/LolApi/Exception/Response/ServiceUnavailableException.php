<?php namespace URFBattleground\Managers\LolApi\Exception\Response;

use URFBattleground\Managers\LolApi\Api\Response\Response;

class ServiceUnavailableException extends ApiResponseException {

	public function __construct(Response $response, $message = 'Service unavailable', $code = 503, \Exception $previous = null)
	{
		parent::__construct($response, $message, $code, $previous);
	}

}
