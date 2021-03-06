<?php namespace URFBattleground\Managers\LolApi\Exception\Response;

use URFBattleground\Managers\LolApi\Api\Response\Response;

class LimitExceedException extends ApiResponseException {

	public function __construct(Response $response, $message = 'Limit Exceed', $code = 429, \Exception $previous = null)
	{
		parent::__construct($response, $message, $code, $previous);
	}

}
