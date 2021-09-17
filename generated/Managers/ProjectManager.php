<?php

namespace EmsEncrypt\Api\Managers;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;
use EmsEncrypt\Api\Resources\ProjectListResponse;
use EmsEncrypt\Api\Resources\ErrorResponse;
use EmsEncrypt\Api\Resources\ProjectResponse;
use EmsEncrypt\Api\Resources\Project;
use EmsEncrypt\Api\Resources\Meta;
use EmsEncrypt\Api\Resources\Pagination;

/**
 * Project manager class
 * 
 * @package EmsEncrypt\Api\Managers
 */
class ProjectManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Project manager class constructor
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
	 * Show project list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return ProjectListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/project';

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

		$response = new ProjectListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new Project(
					$this->apiClient, 
					$data['id'], 
					(isset($data['search_engine_id']) ? $data['search_engine_id'] : null), 
					$data['name'], 
					$data['public_key'], 
					(isset($data['authorization_webhook_url']) ? $data['authorization_webhook_url'] : null), 
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
	 * Create and store a new project
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $name
	 * @param string $authorization_webhook_url Format: url.
	 * @param string $authorization_webhook_auth_username
	 * @param string $authorization_webhook_auth_password
	 * 
	 * @return ProjectResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($name, $authorization_webhook_url = null, $authorization_webhook_auth_username = null, $authorization_webhook_auth_password = null)
	{
		$routeUrl = '/api/project';

		$bodyParameters = [];
		$bodyParameters['name'] = $name;

		if (!is_null($authorization_webhook_url)) {
			$bodyParameters['authorization_webhook_url'] = $authorization_webhook_url;
		}

		if (!is_null($authorization_webhook_auth_username)) {
			$bodyParameters['authorization_webhook_auth_username'] = $authorization_webhook_auth_username;
		}

		if (!is_null($authorization_webhook_auth_password)) {
			$bodyParameters['authorization_webhook_auth_password'] = $authorization_webhook_auth_password;
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

		$response = new ProjectResponse(
			$this->apiClient, 
			new Project(
				$this->apiClient, 
				$requestBody['data']['id'], 
				(isset($requestBody['data']['search_engine_id']) ? $requestBody['data']['search_engine_id'] : null), 
				$requestBody['data']['name'], 
				$requestBody['data']['public_key'], 
				(isset($requestBody['data']['authorization_webhook_url']) ? $requestBody['data']['authorization_webhook_url'] : null), 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Get specified project
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $projectId Project UUID
	 * 
	 * @return ProjectResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($projectId)
	{
		$routePath = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $projectId,
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

		$response = new ProjectResponse(
			$this->apiClient, 
			new Project(
				$this->apiClient, 
				$requestBody['data']['id'], 
				(isset($requestBody['data']['search_engine_id']) ? $requestBody['data']['search_engine_id'] : null), 
				$requestBody['data']['name'], 
				$requestBody['data']['public_key'], 
				(isset($requestBody['data']['authorization_webhook_url']) ? $requestBody['data']['authorization_webhook_url'] : null), 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Update a specified project
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $projectId Project UUID
	 * @param string $name
	 * @param string $authorization_webhook_url Format: url.
	 * @param string $authorization_webhook_auth_username
	 * @param string $authorization_webhook_auth_password
	 * 
	 * @return ProjectResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($projectId, $name, $authorization_webhook_url = null, $authorization_webhook_auth_username = null, $authorization_webhook_auth_password = null)
	{
		$routePath = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $projectId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['name'] = $name;

		if (!is_null($authorization_webhook_url)) {
			$bodyParameters['authorization_webhook_url'] = $authorization_webhook_url;
		}

		if (!is_null($authorization_webhook_auth_username)) {
			$bodyParameters['authorization_webhook_auth_username'] = $authorization_webhook_auth_username;
		}

		if (!is_null($authorization_webhook_auth_password)) {
			$bodyParameters['authorization_webhook_auth_password'] = $authorization_webhook_auth_password;
		}

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('patch', $routeUrl, $requestOptions);

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

		$response = new ProjectResponse(
			$this->apiClient, 
			new Project(
				$this->apiClient, 
				$requestBody['data']['id'], 
				(isset($requestBody['data']['search_engine_id']) ? $requestBody['data']['search_engine_id'] : null), 
				$requestBody['data']['name'], 
				$requestBody['data']['public_key'], 
				(isset($requestBody['data']['authorization_webhook_url']) ? $requestBody['data']['authorization_webhook_url'] : null), 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified project
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $projectId Project UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($projectId)
	{
		$routePath = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $projectId,
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
