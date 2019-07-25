<?php

namespace EmsEncrypt\Api\Resources;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;

/**
 * WebhookCall resource class
 * 
 * @package EmsEncrypt\Api\Resources
 */
class WebhookCall 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $id;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $webhook_id;

	/**
	 * Format: json.
	 * 
	 * @var string
	 */
	public $payload;

	/**
	 * @var string
	 */
	public $status;

	/**
	 * @var string
	 */
	public $response_code;

	/**
	 * @var string
	 */
	public $response_content;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $started_at;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $finished_at;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $created_at;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $updated_at;

	/**
	 * WebhookCall resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $webhook_id Format: uuid.
	 * @param string $payload Format: json.
	 * @param string $status
	 * @param string $response_code
	 * @param string $response_content
	 * @param string $started_at Format: date-time.
	 * @param string $finished_at Format: date-time.
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $webhook_id = null, $payload = null, $status = null, $response_code = null, $response_content = null, $started_at = null, $finished_at = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->webhook_id = $webhook_id;
		$this->payload = $payload;
		$this->status = $status;
		$this->response_code = $response_code;
		$this->response_content = $response_content;
		$this->started_at = $started_at;
		$this->finished_at = $finished_at;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Delete specified webhook call
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/webhookCall/{webhookCallId}';

		$pathReplacements = [
			'{webhookCallId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('delete', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 204) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 204, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new ErrorResponse(
			$this->apiClient, 
			$requestBody['message'], 
			(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
			(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
			(isset($requestBody['debug']) ? $requestBody['debug'] : null)
		);

		return $response;
	}
}
