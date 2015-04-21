<?php namespace URFBattleground\Managers\LolApi\Engine\Exception;

use Exception;

class InvalidStorageInstanceException extends InvalidInstanceException {

	public function __construct($message = "Storage instance must implement StorageInterface", $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}


}
