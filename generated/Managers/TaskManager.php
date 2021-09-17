<?php

namespace EmsEncrypt\Api\Managers;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;
use EmsEncrypt\Api\Resources\TaskListResponse;
use EmsEncrypt\Api\Resources\ErrorResponse;
use EmsEncrypt\Api\Resources\TaskResponse;
use EmsEncrypt\Api\Resources\Task;
use EmsEncrypt\Api\Resources\Meta;
use EmsEncrypt\Api\Resources\Pagination;

/**
 * Task manager class
 * 
 * @package EmsEncrypt\Api\Managers
 */
class TaskManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Task manager class constructor
	 *
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 */
	public function __construct(ApiClient $apiClient)
	{
		$this->apiClient = $apiClient;
	}

	/**
	 * Return the API client used for this manager requests
	 *
	 * @return ApiClient
	 */
	public function getApiClient()
	{
		return $this->apiClient;
	}

	/**
	 * Show task list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return TaskListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/task';

		$queryParameters = [];

		if (!is_null($include)) {
			$queryParameters['include'] = $include;
		}

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

		$response = new TaskListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new Task(
					$this->apiClient, 
					$data['id'], 
					$data['project_id'], 
					$data['user_id'], 
					$data['task_type_id'], 
					$data['server_id'], 
					$data['domain'], 
					$data['order_id'], 
					$data['status'], 
					$data['output'], 
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
	
	/**
	 * Create and store a new task
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $project_id Format: uuid.
	 * @param string $task_type_id
	 * @param string $server_id Format: uuid.
	 * @param string $domain
	 * 
	 * @return TaskResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($project_id, $task_type_id, $server_id = null, $domain = null)
	{
		$routeUrl = '/api/task';

		$bodyParameters = [];
		$bodyParameters['project_id'] = $project_id;
		$bodyParameters['task_type_id'] = $task_type_id;

		if (!is_null($server_id)) {
			$bodyParameters['server_id'] = $server_id;
		}

		if (!is_null($domain)) {
			$bodyParameters['domain'] = $domain;
		}

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('post', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 201) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				(isset($requestBody['message']) ? $requestBody['message'] : null), 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 201, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new TaskResponse(
			$this->apiClient, 
			new Task(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['project_id'], 
				$requestBody['data']['user_id'], 
				$requestBody['data']['task_type_id'], 
				$requestBody['data']['server_id'], 
				$requestBody['data']['domain'], 
				$requestBody['data']['order_id'], 
				$requestBody['data']['status'], 
				$requestBody['data']['output'], 
				$requestBody['data']['started_at'], 
				$requestBody['data']['finished_at'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Get specified task
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $taskId Task UUID
	 * 
	 * @return TaskResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($taskId)
	{
		$routePath = '/api/task/{taskId}';

		$pathReplacements = [
			'{taskId}' => $taskId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

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

		$response = new TaskResponse(
			$this->apiClient, 
			new Task(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['project_id'], 
				$requestBody['data']['user_id'], 
				$requestBody['data']['task_type_id'], 
				$requestBody['data']['server_id'], 
				$requestBody['data']['domain'], 
				$requestBody['data']['order_id'], 
				$requestBody['data']['status'], 
				$requestBody['data']['output'], 
				$requestBody['data']['started_at'], 
				$requestBody['data']['finished_at'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified task
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $taskId Task UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($taskId)
	{
		$routePath = '/api/task/{taskId}';

		$pathReplacements = [
			'{taskId}' => $taskId,
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
