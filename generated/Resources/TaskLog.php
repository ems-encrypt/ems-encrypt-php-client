<?php

namespace EmsEncrypt\Api\Resources;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;

/**
 * TaskLog resource class
 * 
 * @package EmsEncrypt\Api\Resources
 */
class TaskLog 
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
	public $server_id;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $task_id;

	/**
	 * @var string
	 */
	public $status;

	/**
	 * @var string
	 */
	public $output;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $position;

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
	 * TaskLog resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $server_id Format: uuid.
	 * @param string $task_id Format: uuid.
	 * @param string $status
	 * @param string $output
	 * @param int $position Format: int32.
	 * @param string $started_at Format: date-time.
	 * @param string $finished_at Format: date-time.
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $server_id = null, $task_id = null, $status = null, $output = null, $position = null, $started_at = null, $finished_at = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->server_id = $server_id;
		$this->task_id = $task_id;
		$this->status = $status;
		$this->output = $output;
		$this->position = $position;
		$this->started_at = $started_at;
		$this->finished_at = $finished_at;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Delete specified task log
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/taskLog/{taskLogId}';

		$pathReplacements = [
			'{taskLogId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('delete', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 204) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				(isset($requestBody['message']) ? $requestBody['message'] : null), 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 204, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new ErrorResponse(
			$this->apiClient, 
			(isset($requestBody['message']) ? $requestBody['message'] : null), 
			(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
			(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
			(isset($requestBody['debug']) ? $requestBody['debug'] : null)
		);

		return $response;
	}
}
