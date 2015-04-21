<?php namespace URFBattleground\Managers\LolApi\Exception\Response;

use URFBattleground\Managers\LolApi\Api\Response\Response;

class MethodNotAllowed extends ApiResponseException {

	public function __construct(Response $response, $message = 'Method Not Allowed', $code = 405, \Exception $previous = null)
	{
		parent::__construct($response, $message, $code, $previous);
	}

}
