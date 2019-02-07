<?php

namespace EmsEncrypt\Api\Resources;

use EmsEncrypt\Api\ApiClient;
use EmsEncrypt\Api\Exceptions\UnexpectedResponseException;

/**
 * Order resource class
 * 
 * @package EmsEncrypt\Api\Resources
 */
class Order 
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
	public $key_type;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $key_size;

	/**
	 * Format: json.
	 * 
	 * @var string
	 */
	public $domains;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $not_before;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $not_after;

	/**
	 * @var string
	 */
	public $url;

	/**
	 * @var string
	 */
	public $status;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $expires;

	/**
	 * Format: json.
	 * 
	 * @var string
	 */
	public $identifiers;

	/**
	 * Format: json.
	 * 
	 * @var string
	 */
	public $authorization_urls;

	/**
	 * Format: json.
	 * 
	 * @var string
	 */
	public $authorizations;

	/**
	 * @var string
	 */
	public $finalize_url;

	/**
	 * @var string
	 */
	public $certificate_url;

	/**
	 * @var string
	 */
	public $certificate_private_key;

	/**
	 * @var string
	 */
	public $certificate_public_key;

	/**
	 * @var string
	 */
	public $certificate_certificate;

	/**
	 * @var string
	 */
	public $certificate_fullchain;

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
	 * Order resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $domain
	 * @param string $project_id Format: uuid.
	 * @param string $key_type
	 * @param int $key_size Format: int32.
	 * @param string $domains Format: json.
	 * @param string $not_before Format: date-time.
	 * @param string $not_after Format: date-time.
	 * @param string $url
	 * @param string $status
	 * @param string $expires Format: date-time.
	 * @param string $identifiers Format: json.
	 * @param string $authorization_urls Format: json.
	 * @param string $authorizations Format: json.
	 * @param string $finalize_url
	 * @param string $certificate_url
	 * @param string $certificate_private_key
	 * @param string $certificate_public_key
	 * @param string $certificate_certificate
	 * @param string $certificate_fullchain
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $domain = null, $project_id = null, $key_type = null, $key_size = null, $domains = null, $not_before = null, $not_after = null, $url = null, $status = null, $expires = null, $identifiers = null, $authorization_urls = null, $authorizations = null, $finalize_url = null, $certificate_url = null, $certificate_private_key = null, $certificate_public_key = null, $certificate_certificate = null, $certificate_fullchain = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->domain = $domain;
		$this->project_id = $project_id;
		$this->key_type = $key_type;
		$this->key_size = $key_size;
		$this->domains = $domains;
		$this->not_before = $not_before;
		$this->not_after = $not_after;
		$this->url = $url;
		$this->status = $status;
		$this->expires = $expires;
		$this->identifiers = $identifiers;
		$this->authorization_urls = $authorization_urls;
		$this->authorizations = $authorizations;
		$this->finalize_url = $finalize_url;
		$this->certificate_url = $certificate_url;
		$this->certificate_private_key = $certificate_private_key;
		$this->certificate_public_key = $certificate_public_key;
		$this->certificate_certificate = $certificate_certificate;
		$this->certificate_fullchain = $certificate_fullchain;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
}
