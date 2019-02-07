<?php

namespace EmsEncrypt\Api\Resources;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;

/**
 * ServerHasCertificate resource class
 * 
 * @package EmsEncrypt\Api\Resources
 */
class ServerHasCertificate 
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
	public $server_id;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $project_id;

	/**
	 * @var string
	 */
	public $domain;

	/**
	 * @var string
	 */
	public $status;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $status_updated_at;

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
	 * ServerHasCertificate resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $server_id Format: uuid.
	 * @param string $project_id Format: uuid.
	 * @param string $domain
	 * @param string $status
	 * @param string $status_updated_at Format: date-time.
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $server_id = null, $project_id = null, $domain = null, $status = null, $status_updated_at = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->server_id = $server_id;
		$this->project_id = $project_id;
		$this->domain = $domain;
		$this->status = $status;
		$this->status_updated_at = $status_updated_at;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
}
