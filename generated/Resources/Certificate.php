<?php

namespace EmsEncrypt\Api\Resources;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;

/**
 * Certificate resource class
 * 
 * @package EmsEncrypt\Api\Resources
 */
class Certificate 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var string
	 */
	public $domain;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $project_id;

	/**
	 * @var string
	 */
	public $status;

	/**
	 * @var string
	 */
	public $cert_pem;

	/**
	 * @var string
	 */
	public $chain_pem;

	/**
	 * @var string
	 */
	public $fullchain_pem;

	/**
	 * @var string
	 */
	public $private_key_pem;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $expires_at;

	/**
	 * @var boolean
	 */
	public $lets_encrypt;

	/**
	 * @var string
	 */
	public $lets_encrypt_challenge_type;

	/**
	 * @var boolean
	 */
	public $wildcard;

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
	 * Certificate resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $domain
	 * @param string $project_id Format: uuid.
	 * @param string $status
	 * @param string $cert_pem
	 * @param string $chain_pem
	 * @param string $fullchain_pem
	 * @param string $private_key_pem
	 * @param string $expires_at Format: date-time.
	 * @param boolean $lets_encrypt
	 * @param string $lets_encrypt_challenge_type
	 * @param boolean $wildcard
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $domain = null, $project_id = null, $status = null, $cert_pem = null, $chain_pem = null, $fullchain_pem = null, $private_key_pem = null, $expires_at = null, $lets_encrypt = null, $lets_encrypt_challenge_type = null, $wildcard = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->domain = $domain;
		$this->project_id = $project_id;
		$this->status = $status;
		$this->cert_pem = $cert_pem;
		$this->chain_pem = $chain_pem;
		$this->fullchain_pem = $fullchain_pem;
		$this->private_key_pem = $private_key_pem;
		$this->expires_at = $expires_at;
		$this->lets_encrypt = $lets_encrypt;
		$this->lets_encrypt_challenge_type = $lets_encrypt_challenge_type;
		$this->wildcard = $wildcard;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Delete specified certificate
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/certificate/{domain},{projectId}';

		$pathReplacements = [
			'{domain}' => $this->domain,
			'{projectId}' => $this->project_id,
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
	 * Show certificate order list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return OrderListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getOrders($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/certificate/{domain},{projectId}/order';

		$pathReplacements = [
			'{domain}' => $this->domain,
			'{projectId}' => $this->project_id,
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

		$response = new OrderListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new Order(
					$this->apiClient, 
					$data['id'], 
					$data['domain'], 
					$data['project_id'], 
					$data['key_type'], 
					$data['key_size'], 
					$data['domains'], 
					$data['not_before'], 
					$data['not_after'], 
					$data['url'], 
					$data['status'], 
					$data['expires'], 
					$data['identifiers'], 
					$data['authorization_urls'], 
					$data['authorizations'], 
					$data['finalize_url'], 
					$data['certificate_url'], 
					$data['certificate_private_key'], 
					$data['certificate_public_key'], 
					$data['certificate_certificate'], 
					$data['certificate_fullchain'], 
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
	 * Show certificate task list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return TaskListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getTasks($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/certificate/{domain},{projectId}/task';

		$pathReplacements = [
			'{domain}' => $this->domain,
			'{projectId}' => $this->project_id,
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
	 * Show certificate server has certificate list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return ServerHasCertificateListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getServerHasCertificates($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/certificate/{domain},{projectId}/serverHasCertificate';

		$pathReplacements = [
			'{domain}' => $this->domain,
			'{projectId}' => $this->project_id,
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

		$response = new ServerHasCertificateListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new ServerHasCertificate(
					$this->apiClient, 
					$data['server_id'], 
					$data['project_id'], 
					$data['domain'], 
					$data['status'], 
					$data['status_updated_at'], 
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
