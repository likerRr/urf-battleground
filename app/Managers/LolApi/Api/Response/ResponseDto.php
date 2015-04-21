<?php namespace URFBattleground\Managers\LolApi\Api\Response;

class ResponseDto {

	/** @var Response */
	private $response;

	public function __construct(Response $response)
	{
		$this->response = $response;
	}

	public function response()
	{
		return $this->response;
	}

}
