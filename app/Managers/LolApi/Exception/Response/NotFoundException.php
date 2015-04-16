<?php namespace URFBattleground\Managers\LolApi\Exception\Response;

use URFBattleground\Managers\LolApi\Api\Response\Response;

class NotFoundException extends ApiResponseException {

	public function __construct(Response $response, $message = 'Not found', $code = 404, \Exception $previous = null)
	{
		parent::__construct($response, $message, $code, $previous);
	}

}
