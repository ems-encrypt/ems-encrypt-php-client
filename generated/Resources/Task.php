<?php

namespace EmsEncrypt\Api\Resources;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;

/**
 * Task resource class
 * 
 * @package EmsEncrypt\Api\Resources
 */
class Task 
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
	public $project_id;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $user_id;

	/**
	 * @var string
	 */
	public $task_type_id;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $server_id;

	/**
	 * @var string
	 */
	public $domain;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $order_id;

	/**
	 * @var string
	 */
	public $status;

	/**
	 * @var string
	 */
	public $output;

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
	 * Task resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $project_id Format: uuid.
	 * @param string $user_id Format: uuid.
	 * @param string $task_type_id
	 * @param string $server_id Format: uuid.
	 * @param string $domain
	 * @param string $order_id Format: uuid.
	 * @param string $status
	 * @param string $output
	 * @param string $started_at Format: date-time.
	 * @param string $finished_at Format: date-time.
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $project_id = null, $user_id = null, $task_type_id = null, $server_id = null, $domain = null, $order_id = null, $status = null, $output = null, $started_at = null, $finished_at = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->project_id = $project_id;
		$this->user_id = $user_id;
		$this->task_type_id = $task_type_id;
		$this->server_id = $server_id;
		$this->domain = $domain;
		$this->order_id = $order_id;
		$this->status = $status;
		$this->output = $output;
		$this->started_at = $started_at;
		$this->finished_at = $finished_at;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Delete specified task
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/task/{taskId}';

		$pathReplacements = [
			'{taskId}' => $this->id,
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
	
	/**
	 * Show task task log list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return TaskLogListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getTaskLogs($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/task/{taskId}/taskLog';

		$pathReplacements = [
			'{taskId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$queryParameters = [];

		if (!is_null($search)) {
			$queryParameters['search'] = $search;
		}

		if (!is_null($page)) {
			$queryParameters['page'] = $page;
		}

		if (!is_null($limit)) {
			$queryParameters['limit'] = $limit;
		}

		if (!is_null($order_by)) {
			$queryParameters['order_by'] = $order_by;
		}

		$requestOptions = [];
		$requestOptions['query'] = $queryParameters;

		$request = $this->apiClient->getHttpClient()->request('get', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 200) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				(isset($requestBody['message']) ? $requestBody['message'] : null), 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new TaskLogListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new TaskLog(
					$this->apiClient, 
					$data['id'], 
					$data['server_id'], 
					$data['task_id'], 
					$data['status'], 
					$data['output'], 
					$data['position'], 
					$data['started_at'], 
					$data['finished_at'], 
					$data['created_at'], 
					$data['updated_at']
				); 
			}, $requestBody['data']), 
			new Meta(
				$this->apiClient, 
				((isset($requestBody['meta']['pagination']) && !is_null($requestBody['meta']['pagination'])) ? (new Pagination(
					$this->apiClient, 
					$requestBody['meta']['pagination']['total'], 
					$requestBody['meta']['pagination']['count'], 
					$requestBody['meta']['pagination']['per_page'], 
					$requestBody['meta']['pagination']['current_page'], 
					$requestBody['meta']['pagination']['total_pages'], 
					$requestBody['meta']['pagination']['links']
				)) : null)
			)
		);

		return $response;
	}
}
