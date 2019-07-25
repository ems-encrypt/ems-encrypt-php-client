<?php

namespace EmsEncrypt\Api\Resources;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;

/**
 * WebhookResponse resource class
 * 
 * @package EmsEncrypt\Api\Resources
 */
class WebhookResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var Webhook
	 */
	public $data;

	/**
	 * WebhookResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param Webhook $data
	 */
	public function __construct(ApiClient $apiClient, $data = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
	}
}
