<?php

namespace EmsEncrypt\Api\Managers;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;
use EmsEncrypt\Api\Resources\OrderListResponse;
use EmsEncrypt\Api\Resources\ErrorResponse;
use EmsEncrypt\Api\Resources\OrderResponse;
use EmsEncrypt\Api\Resources\Order;
use EmsEncrypt\Api\Resources\Meta;
use EmsEncrypt\Api\Resources\Pagination;

/**
 * Order manager class
 * 
 * @package EmsEncrypt\Api\Managers
 */
class OrderManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Order manager class constructor
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
	 * Show order list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return OrderListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/order';

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
	 * Get specified order
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $orderId Order UUID
	 * 
	 * @return OrderResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($orderId)
	{
		$routePath = '/api/order/{orderId}';

		$pathReplacements = [
			'{orderId}' => $orderId,
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

		$response = new OrderResponse(
			$this->apiClient, 
			new Order(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['domain'], 
				$requestBody['data']['project_id'], 
				$requestBody['data']['key_type'], 
				$requestBody['data']['key_size'], 
				$requestBody['data']['domains'], 
				$requestBody['data']['not_before'], 
				$requestBody['data']['not_after'], 
				$requestBody['data']['url'], 
				$requestBody['data']['status'], 
				$requestBody['data']['expires'], 
				$requestBody['data']['identifiers'], 
				$requestBody['data']['authorization_urls'], 
				$requestBody['data']['authorizations'], 
				$requestBody['data']['finalize_url'], 
				$requestBody['data']['certificate_url'], 
				$requestBody['data']['certificate_private_key'], 
				$requestBody['data']['certificate_public_key'], 
				$requestBody['data']['certificate_certificate'], 
				$requestBody['data']['certificate_fullchain'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
}
