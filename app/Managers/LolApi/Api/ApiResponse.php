<?php namespace URFBattleground\Managers\LolApi\Api;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\Response;

class ApiResponse {

	/** @var Response|ClientException */
	private $response;

	/** @var  boolean */
	private $ok = false;
	private $code;
	private $message = '';
	private $data = [];
	private $isRateExceeded = false;
	private $resource;

	private $apiResponse;

	public function __construct($response)
	{
		if (!($response instanceof Response) && !($response instanceof ClientException)) {
			throw new \Exception('Response object can be instance of GuzzleHttp\Message\Response or GuzzleHttp\Exception\ClientException');
		}

		if ($response instanceof Response) {
			$this->ok = true;
		}

		$this->response = $response;
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
		$this->data = $this->response->json();
		$this->code = $this->response->getStatusCode();
		$this->resource = $this->response->getEffectiveUrl();
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

			if ($this->code == 429) {
				$this->isRateExceeded = true;
			}
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
	 * @return boolean
	 */
	public function isIsRateExceeded()
	{
		return $this->isRateExceeded;
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
	 * @return ClientException|Response
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
