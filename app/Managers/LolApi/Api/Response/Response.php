<?php namespace URFBattleground\Managers\LolApi\Api\Response;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\Response as ClientResponse;
use URFBattleground\Managers\LolApi\Engine\Api\Constants\ResponseCode as Code;
use URFBattleground\Managers\LolApi\Exception\Response\ApiResponseException;
use URFBattleground\Managers\LolApi\Exception\Response\BadRequestException;
use URFBattleground\Managers\LolApi\Exception\Response\InternalServerErrorException;
use URFBattleground\Managers\LolApi\Exception\Response\LimitExceedException;
use URFBattleground\Managers\LolApi\Exception\Response\NotFoundException;
use URFBattleground\Managers\LolApi\Exception\Response\ServiceUnavailableException;
use URFBattleground\Managers\LolApi\Exception\Response\UnauthorizedException;
use URFBattleground\Managers\LolApi\Exception\UnexpectedException;
use URFBattleground\Managers\LolApi\Exception\UnknownResponseException;

class Response {

	/** @var ClientResponse|ClientException|ResponseCached */
	private $response;

	/** @var  boolean */
	private $ok = false;
	private $code;
	private $message = '';
	private $data = [];
	private $dataObj;
	private $resource;
	private $storeTime;
	private $cached;
	private $apiResponseException;
	private $validErrorCodes = [
		Code::SERVICE_UNAVAILABLE,
		Code::INTERNAL_SERVER_ERROR,
		Code::BAD_REQUEST,
		Code::LIMIT_EXCEED,
		Code::NOT_FOUND,
		Code::UNAUTHORIZED
	];

	private $apiResponse;

	public function __construct($response, $storeTime = 0)
	{
		if (!($response instanceof ClientResponse) &&
			!($response instanceof ClientException) &&
			!($response instanceof ResponseCached)
		) {
			throw new UnknownResponseException($response);
		}

		if ($response instanceof ClientException) {
			if (!in_array($response->getCode(), $this->validErrorCodes)) {
				throw new UnexpectedException($response->getMessage(), $response->getCode(), $response);
			}
		}

		if ($response instanceof ClientResponse || $response instanceof ResponseCached || $storeTime) {
			$this->ok = true;
		}

		$this->cached = ($response instanceof ResponseCached);
		$this->response = $response;
		$this->storeTime = $storeTime;
		$this->dataObj = new \stdClass();
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
		$this->throwResponseExceptionIfSet();
	}

	private function throwResponseExceptionIfSet()
	{
		if ($this->apiResponseException instanceof ApiResponseException) {
			throw $this->apiResponseException;
		}
	}

	private function handleOk()
	{
		$key = $this->response->getEffectiveUrl();
		if ($this->cached) {
			$this->resource = $this->response->getEffectiveUrl();
			$this->data = $this->response->getData();
			$this->dataObj = $this->response->getDataObj();
			$this->code = $this->response->getCode();
			if ($this->storeTime === -1) {
				$this->response->forget();
			}
		} else {
			$this->resource = $this->response->getEffectiveUrl();
			$this->data = $this->response->json();
			$this->dataObj = $this->response->json(['object' => true]);
			$this->code = $this->response->getStatusCode();

			$cachedResponse = [
				'resource' => $this->resource,
				'data' => $this->data,
				'dataObj' => $this->dataObj,
				'code' => $this->code
			];
			(new ResponseCached($key))->put($cachedResponse, $this->storeTime);
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
			$this->handleErrorCodes($this->code);
		}
	}

	private function handleErrorCodes($code)
	{
		switch ($code) {
			case Code::BAD_REQUEST: {
				$this->apiResponseException = new BadRequestException($this);
				break;
			}
			case Code::UNAUTHORIZED: {
				$this->apiResponseException = new UnauthorizedException($this);
				break;
			}
			case Code::NOT_FOUND: {
				$this->apiResponseException = new NotFoundException($this);
				break;
			}
			case Code::LIMIT_EXCEED: {
				$this->apiResponseException = new LimitExceedException($this);
				// plus one to not to get "Retry-After 0"
				$readyAfter = (int) $this->response->getResponse()->getHeader('Retry-After');
				\LolApi::setReadyAfter($readyAfter + 1);
				break;
			}
			case Code::INTERNAL_SERVER_ERROR: {
				$this->apiResponseException = new InternalServerErrorException($this);
				break;
			}
			case Code::SERVICE_UNAVAILABLE: {
				$this->apiResponseException = new ServiceUnavailableException($this);
				break;
			}
			default: $this->apiResponseException = new ApiResponseException($this);
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
	public function getDataObj()
	{
		return $this->dataObj;
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
