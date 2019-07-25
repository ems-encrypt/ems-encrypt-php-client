<?php

namespace EmsEncrypt\Api\Resources;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;

/**
 * Webhook resource class
 * 
 * @package EmsEncrypt\Api\Resources
 */
class Webhook 
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
	 * @var string
	 */
	public $on_event;

	/**
	 * Format: url.
	 * 
	 * @var string
	 */
	public $url;

	/**
	 * @var string
	 */
	public $basic_auth_username;

	/**
	 * @var string
	 */
	public $basic_auth_password;

	/**
	 * @var boolean
	 */
	public $enabled;

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
	 * Webhook resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $project_id Format: uuid.
	 * @param string $on_event
	 * @param string $url Format: url.
	 * @param string $basic_auth_username
	 * @param string $basic_auth_password
	 * @param boolean $enabled
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $project_id = null, $on_event = null, $url = null, $basic_auth_username = null, $basic_auth_password = null, $enabled = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->project_id = $project_id;
		$this->on_event = $on_event;
		$this->url = $url;
		$this->basic_auth_username = $basic_auth_username;
		$this->basic_auth_password = $basic_auth_password;
		$this->enabled = $enabled;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Update a specified webhook
	 * 
	 * Excepted HTTP code : 200
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
	public function update($project_id, $on_event, $enabled, $url = null, $basic_auth_username = null, $basic_auth_password = null)
	{
		$routePath = '/api/webhook/{webhookId}';

		$pathReplacements = [
			'{webhookId}' => $this->id,
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
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/webhook/{webhookId}';

		$pathReplacements = [
			'{webhookId}' => $this->id,
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
	
	/**
	 * Show webhook webhook calls list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return WebhookCallListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getWebhookCalls($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/webhook/{webhookId}/webhookCall';

		$pathReplacements = [
			'{webhookId}' => $this->id,
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
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new WebhookCallListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new WebhookCall(
					$this->apiClient, 
					$data['id'], 
					$data['webhook_id'], 
					$data['payload'], 
					$data['status'], 
					$data['response_code'], 
					$data['response_content'], 
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
