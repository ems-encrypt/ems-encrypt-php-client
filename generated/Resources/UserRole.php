<?php

namespace EmsEncrypt\Api\Resources;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;

/**
 * UserRole resource class
 * 
 * @package EmsEncrypt\Api\Resources
 */
class UserRole 
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
	public $id;

	/**
	 * UserRole resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id
	 */
	public function __construct(ApiClient $apiClient, $id = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
	}
}
