<?php

namespace EmsEncrypt\Api\Managers;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;
use EmsEncrypt\Api\Resources\CertificateListResponse;
use EmsEncrypt\Api\Resources\ErrorResponse;
use EmsEncrypt\Api\Resources\CertificateResponse;
use EmsEncrypt\Api\Resources\Certificate;
use EmsEncrypt\Api\Resources\Meta;
use EmsEncrypt\Api\Resources\Pagination;

/**
 * Certificate manager class
 * 
 * @package EmsEncrypt\Api\Managers
 */
class CertificateManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Certificate manager class constructor
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
	 * Show certificate list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return CertificateListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/certificate';

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

		$response = new CertificateListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new Certificate(
					$this->apiClient, 
					$data['domain'], 
					$data['project_id'], 
					$data['status'], 
					$data['cert_pem'], 
					$data['chain_pem'], 
					$data['fullchain_pem'], 
					$data['private_key_pem'], 
					$data['expires_at'], 
					$data['lets_encrypt'], 
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
	 * Create and store a new certificate
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $domain
	 * @param string $project_id Format: uuid.
	 * @param string $status
	 * @param string $cert_pem
	 * @param string $chain_pem
	 * @param string $fullchain_pem
	 * @param string $private_key_pem
	 * @param string $expires_at Must be a valid date according to the strtotime PHP function.
	 * @param boolean $lets_encrypt
	 * 
	 * @return CertificateResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($domain, $project_id, $status = null, $cert_pem = null, $chain_pem = null, $fullchain_pem = null, $private_key_pem = null, $expires_at = null, $lets_encrypt = null)
	{
		$routeUrl = '/api/certificate';

		$bodyParameters = [];
		$bodyParameters['domain'] = $domain;
		$bodyParameters['project_id'] = $project_id;

		if (!is_null($status)) {
			$bodyParameters['status'] = $status;
		}

		if (!is_null($cert_pem)) {
			$bodyParameters['cert_pem'] = $cert_pem;
		}

		if (!is_null($chain_pem)) {
			$bodyParameters['chain_pem'] = $chain_pem;
		}

		if (!is_null($fullchain_pem)) {
			$bodyParameters['fullchain_pem'] = $fullchain_pem;
		}

		if (!is_null($private_key_pem)) {
			$bodyParameters['private_key_pem'] = $private_key_pem;
		}

		if (!is_null($expires_at)) {
			$bodyParameters['expires_at'] = $expires_at;
		}

		if (!is_null($lets_encrypt)) {
			$bodyParameters['lets_encrypt'] = $lets_encrypt;
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

		$response = new CertificateResponse(
			$this->apiClient, 
			new Certificate(
				$this->apiClient, 
				$requestBody['data']['domain'], 
				$requestBody['data']['project_id'], 
				$requestBody['data']['status'], 
				$requestBody['data']['cert_pem'], 
				$requestBody['data']['chain_pem'], 
				$requestBody['data']['fullchain_pem'], 
				$requestBody['data']['private_key_pem'], 
				$requestBody['data']['expires_at'], 
				$requestBody['data']['lets_encrypt'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Get specified certificate
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $domain Domain
	 * @param string $projectId Project ID
	 * 
	 * @return CertificateResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($domain, $projectId)
	{
		$routePath = '/api/certificate/{domain},{projectId}';

		$pathReplacements = [
			'{domain}' => $domain,
			'{projectId}' => $projectId,
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

		$response = new CertificateResponse(
			$this->apiClient, 
			new Certificate(
				$this->apiClient, 
				$requestBody['data']['domain'], 
				$requestBody['data']['project_id'], 
				$requestBody['data']['status'], 
				$requestBody['data']['cert_pem'], 
				$requestBody['data']['chain_pem'], 
				$requestBody['data']['fullchain_pem'], 
				$requestBody['data']['private_key_pem'], 
				$requestBody['data']['expires_at'], 
				$requestBody['data']['lets_encrypt'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified certificate
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $domain Domain
	 * @param string $projectId Project ID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($domain, $projectId)
	{
		$routePath = '/api/certificate/{domain},{projectId}';

		$pathReplacements = [
			'{domain}' => $domain,
			'{projectId}' => $projectId,
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
