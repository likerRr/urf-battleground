<?php namespace URFBattleground\Managers\LolApi\Api\Response;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\Response as ClientResponse;
use URFBattleground\Managers\LolApi\Exception\Response\LimitExceedException;
use URFBattleground\Managers\LolApi\Exception\UnknownResponseException;

class Response {

	/** @var ClientResponse|ClientException|CachedResponse */
	private $response;

	/** @var  boolean */
	private $ok = false;
	private $code;
	private $message = '';
	private $data = [];
	private $resource;
	private $storeTime;
	private $cached;

	private $apiResponse;

	public function __construct($response, $storeTime = 0)
	{
		if (!($response instanceof ClientResponse) &&
			!($response instanceof ClientException) &&
			!($response instanceof CachedResponse)
		) {
			throw new UnknownResponseException($response);
		}

		if ($response instanceof ClientResponse || $storeTime) {
			$this->ok = true;
		}

		$this->cached = ($response instanceof CachedResponse);
		$this->response = $response;
		$this->storeTime = $storeTime;
		$this->buildApiResponse();
	}

	public function json()
	{
		return json_encode($this->apiResponse);
	}

	public function asArray()
	{
		return $this->apiResponse;
	}

	private function buildApiResponse()
	{
		($this->isOk()) ? $this->handleOk() : $this->handleBad();
		$this->apiResponse = $this->getCommonResponse();
	}

	private function handleOk()
	{
		$key = $this->response->getEffectiveUrl();
		if ($this->cached) {
			$this->resource = $this->response->getEffectiveUrl();
			$this->data = $this->response->getData();
			$this->code = $this->response->getCode();
		} else {
			$this->resource = $this->response->getEffectiveUrl();
			$this->data = $this->response->json();
			$this->code = $this->response->getStatusCode();

			$cachedResponse = [
				'resource' => $this->resource,
				'data' => $this->data,
				'code' => $this->code
			];
			(new CachedResponse($key))->put($cachedResponse, $this->storeTime);
		}
	}

	private function handleBad()
	{
		$response = $this->response->getResponse();
		$responseData = $response->json();
		if (isset($responseData['status'])) {
			$responseStatus = $responseData['status'];
			$this->code = $responseStatus['status_code'];
			$this->message = $responseStatus['message'];
			$this->resource = $response->getEffectiveUrl();

			// TODO think, do I really need this throw? Because after that I can't return pretty response
//			if ($this->code == 429) {
//				var_dump($this->getCommonResponse());
//				throw new LimitExceedException;
//			}
		}
	}

	private function getCommonResponse($extended = []) {
		if (!is_array($extended)) {
			$extended = (array) $extended;
		}

		return [
			'error' => array_get($extended, 'success', !$this->isOk()),
			'message' => array_get($extended, 'message', $this->getMessage()),
			'code' => array_get($extended, 'code', $this->getCode()),
			'data' => array_get($extended, 'data', $this->getData()),
		];
	}

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return mixed
	 */
	public function getResource()
	{
		return $this->resource;
	}

	/**
	 * @return ClientException|ClientResponse
	 */
	protected function getResponse() {
		return $this->response;
	}

	public function isOk()
	{
		return $this->ok === true;
	}

	/**
	 * @return mixed
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @return mixed
	 */
	public function getCode()
	{
		return $this->code;
	}

}
