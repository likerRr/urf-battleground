<?php namespace URFBattleground\Managers\LolApi\Exception;

class UnsupportedRegionException extends LolApiGeneralException {

	public function __construct($message = 'Unsupported region', $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}
