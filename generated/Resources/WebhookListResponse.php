<?php

namespace EmsEncrypt\Api\Resources;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;

/**
 * WebhookListResponse resource class
 * 
 * @package EmsEncrypt\Api\Resources
 */
class WebhookListResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var Webhook[]
	 */
	public $data;

	/**
	 * @var Meta
	 */
	public $meta;

	/**
	 * WebhookListResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param Webhook[] $data
	 * @param Meta $meta
	 */
	public function __construct(ApiClient $apiClient, $data = null, $meta = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
		$this->meta = $meta;
	}
}
