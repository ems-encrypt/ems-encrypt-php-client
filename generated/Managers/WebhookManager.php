<?php

namespace EmsEncrypt\Api\Managers;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;
use EmsEncrypt\Api\Resources\WebhookListResponse;
use EmsEncrypt\Api\Resources\ErrorResponse;
use EmsEncrypt\Api\Resources\WebhookResponse;
use EmsEncrypt\Api\Resources\Webhook;
use EmsEncrypt\Api\Resources\Meta;
use EmsEncrypt\Api\Resources\Pagination;

/**
 * Webhook manager class
 * 
 * @package EmsEncrypt\Api\Managers
 */
class WebhookManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Webhook manager class constructor
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
	 * Show webhook list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return WebhookListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/webhook';

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
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new WebhookListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new Webhook(
					$this->apiClient, 
					$data['id'], 
					$data['project_id'], 
					$data['on_event'], 
					$data['url'], 
					$data['basic_auth_username'], 
					$data['basic_auth_password'], 
					$data['enabled'], 
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
	 * Create and store a new webhook
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $project_id Format: uuid.
	 * @param string $on_event
	 * @param boolean $enabled
	 * @param string $url Format: url.
	 * @param string $basic_auth_username
	 * @param string $basic_auth_password
	 * 
	 * @return WebhookResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($project_id, $on_event, $enabled, $url = null, $basic_auth_username = null, $basic_auth_password = null)
	{
		$routeUrl = '/api/webhook';

		$bodyParameters = [];
		$bodyParameters['project_id'] = $project_id;
		$bodyParameters['on_event'] = $on_event;
		$bodyParameters['enabled'] = $enabled;

		if (!is_null($url)) {
			$bodyParameters['url'] = $url;
		}

		if (!is_null($basic_auth_username)) {
			$bodyParameters['basic_auth_username'] = $basic_auth_username;
		}

		if (!is_null($basic_auth_password)) {
			$bodyParameters['basic_auth_password'] = $basic_auth_password;
		}

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('post', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 201) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 201, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new WebhookResponse(
			$this->apiClient, 
			new Webhook(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['project_id'], 
				$requestBody['data']['on_event'], 
				$requestBody['data']['url'], 
				$requestBody['data']['basic_auth_username'], 
				$requestBody['data']['basic_auth_password'], 
				$requestBody['data']['enabled'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Get specified webhook
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $webhookId Webhook UUID
	 * 
	 * @return WebhookResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($webhookId)
	{
		$routePath = '/api/webhook/{webhookId}';

		$pathReplacements = [
			'{webhookId}' => $webhookId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('get', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 200) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new WebhookResponse(
			$this->apiClient, 
			new Webhook(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['project_id'], 
				$requestBody['data']['on_event'], 
				$requestBody['data']['url'], 
				$requestBody['data']['basic_auth_username'], 
				$requestBody['data']['basic_auth_password'], 
				$requestBody['data']['enabled'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Update a specified webhook
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $webhookId Webhook UUID
	 * @param string $project_id Format: uuid.
	 * @param string $on_event
	 * @param boolean $enabled
	 * @param string $url Format: url.
	 * @param string $basic_auth_username
	 * @param string $basic_auth_password
	 * 
	 * @return WebhookResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($webhookId, $project_id, $on_event, $enabled, $url = null, $basic_auth_username = null, $basic_auth_password = null)
	{
		$routePath = '/api/webhook/{webhookId}';

		$pathReplacements = [
			'{webhookId}' => $webhookId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['project_id'] = $project_id;
		$bodyParameters['on_event'] = $on_event;
		$bodyParameters['enabled'] = $enabled;

		if (!is_null($url)) {
			$bodyParameters['url'] = $url;
		}

		if (!is_null($basic_auth_username)) {
			$bodyParameters['basic_auth_username'] = $basic_auth_username;
		}

		if (!is_null($basic_auth_password)) {
			$bodyParameters['basic_auth_password'] = $basic_auth_password;
		}

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('patch', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 200) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new WebhookResponse(
			$this->apiClient, 
			new Webhook(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['project_id'], 
				$requestBody['data']['on_event'], 
				$requestBody['data']['url'], 
				$requestBody['data']['basic_auth_username'], 
				$requestBody['data']['basic_auth_password'], 
				$requestBody['data']['enabled'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified webhook
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $webhookId Webhook UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($webhookId)
	{
		$routePath = '/api/webhook/{webhookId}';

		$pathReplacements = [
			'{webhookId}' => $webhookId,
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
