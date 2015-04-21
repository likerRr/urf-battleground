<?php namespace URFBattleground\Managers\LolApi\Api\Response\Dto;

use URFBattleground\Managers\LolApi\Api\Response\Response;
use URFBattleground\Managers\LolApi\Api\Response\ResponseDto;

class ListDto extends ResponseDto {

	private $list;

	public function __construct(Response $response)
	{
		parent::__construct($response);
		$this->list = new \ArrayIterator($this->response()->getData());
	}

	public function getList($index = null, $default = null)
	{
		if ($index !== null && is_int($index)) {
			return array_get($this->list->getArrayCopy(), $index, $default);
		}

		return $this->list;
	}

}
