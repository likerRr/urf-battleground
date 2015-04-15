<?php namespace URFBattleground\Managers\LolApi\Exception;

class UnknownResponseException extends LolApiGeneralException {

	public function __construct($response, $code = 0, \Exception $previous = null)
	{
		if (is_object($response)) {
			$object = get_class($response);
		} else {
			$object = (string) $response;
		}
		$message = 'Response object can be instance of GuzzleHttp\Message\Response';
		$message .= ' or GuzzleHttp\Exception\ClientException';
		$message .= ' or CachedResponse';
		$message .= ' , but got a ' . $object;
		parent::__construct($message, $code, $previous);
	}


}
