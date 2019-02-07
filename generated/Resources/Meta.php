<?php

namespace EmsEncrypt\Api\Resources;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;

/**
 * Meta resource class
 * 
 * @package EmsEncrypt\Api\Resources
 */
class Meta 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var Pagination
	 */
	public $pagination;

	/**
	 * Meta resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param Pagination $pagination
	 */
	public function __construct(ApiClient $apiClient, $pagination = null)
	{
		$this->apiClient = $apiClient;
		$this->pagination = $pagination;
	}
}
