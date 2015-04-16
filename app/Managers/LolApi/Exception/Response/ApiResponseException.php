<?php namespace URFBattleground\Managers\LolApi\Exception\Response;

use URFBattleground\Managers\LolApi\Api\Response\Response;
use URFBattleground\Managers\LolApi\Exception\LolApiGeneralException;

class ApiResponseException extends LolApiGeneralException {

	/** @var Response */
	private $response;

	public function __construct(Response $response = null, $message = '', $code = 429, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->response = $response;
	}

	/**
	 * @return Response
	 */
	public function getResponse()
	{
		return $this->response;
	}

}
