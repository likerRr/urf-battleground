<?php namespace URFBattleground\Managers\LolApi\Api\Response\Dto;

use URFBattleground\Managers\LolApi\Api\Response\Response;
use URFBattleground\Managers\LolApi\Api\Response\ResponseDto;

class ListDto extends ResponseDto {

	/** @var \ArrayIterator */
	private $list;

	/**
	 * @param Response $response
	 */
	public function __construct(Response $response)
	{
		parent::__construct($response);
		$this->list = new \ArrayIterator((array) $this->response()->getData());
	}

	/**
	 * Returns list of game ids or gets exactly game id by key in array
	 * @param null $index
	 * @param null $default
	 * @return \ArrayIterator|mixed
	 */
	public function getList($index = null, $default = null)
	{
		if ($index !== null && is_int($index)) {
			if (!$this->list->offsetExists($index)) {
				return $default;
			}

			return $this->list->offsetGet($index);
		}

		return $this->list;
	}

}
