<?php

namespace EmsEncrypt\Api\Tests;

use PHPUnit\Framework\TestCase;
use EmsEncrypt\Api\ApiClient;

/**
 * ems-encrypt client test class (test for version 1.0)
 * 
 * @package EmsEncrypt\Api\Tests
 */
class ApiClientTest extends TestCase
{
	public function testCanCreateClient()
	{
		$apiClient = new ApiClient(
			getenv('bearerToken'),
			getenv('apiBaseUrl')
		);

		$this->assertNotNull(
			$apiClient->getHttpClient()
		);
	}
}