<?php

namespace EmsEncrypt\Api;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;
use EmsEncrypt\Api\Managers\MeManager;
use EmsEncrypt\Api\Managers\MeNotificationManager;
use EmsEncrypt\Api\Managers\UserGroupManager;
use EmsEncrypt\Api\Managers\UserManager;
use EmsEncrypt\Api\Managers\I18nLangManager;
use EmsEncrypt\Api\Managers\UserRoleManager;
use EmsEncrypt\Api\Managers\ProjectManager;
use EmsEncrypt\Api\Managers\UserHasProjectManager;
use EmsEncrypt\Api\Managers\ServerManager;
use EmsEncrypt\Api\Managers\TaskTypeManager;
use EmsEncrypt\Api\Managers\ServerTypeManager;
use EmsEncrypt\Api\Managers\TaskManager;
use EmsEncrypt\Api\Managers\TaskLogManager;
use EmsEncrypt\Api\Managers\CertificateManager;
use EmsEncrypt\Api\Managers\OrderManager;
use EmsEncrypt\Api\Managers\ServerHasCertificateManager;

/**
 * ems-encrypt client class (version 1.0)
 * 
 * @package EmsEncrypt\Api
 */
class ApiClient 
{
	/**
	 * API base url for requests
	 *
	 * @var string
	 */
	protected $apiBaseUrl;

	/**
	 * Guzzle client for API requests
	 *
	 * @var GuzzleClient;
	 */
	protected $httpClient;

	/**
	 * Bearer authentication access token
	 *
	 * @var string
	 */
	protected $bearerToken;

	/**
	 * Map of global headers to use with every requests
	 *
	 * @var string[]
	 */
	protected $globalHeaders = [];

	/**
	 * Me manager
	 *
	 * @var MeManager
	 */
	protected $meManager;

	/**
	 * MeNotification manager
	 *
	 * @var MeNotificationManager
	 */
	protected $meNotificationManager;

	/**
	 * UserGroup manager
	 *
	 * @var UserGroupManager
	 */
	protected $userGroupManager;

	/**
	 * User manager
	 *
	 * @var UserManager
	 */
	protected $userManager;

	/**
	 * I18nLang manager
	 *
	 * @var I18nLangManager
	 */
	protected $i18nLangManager;

	/**
	 * UserRole manager
	 *
	 * @var UserRoleManager
	 */
	protected $userRoleManager;

	/**
	 * Project manager
	 *
	 * @var ProjectManager
	 */
	protected $projectManager;

	/**
	 * UserHasProject manager
	 *
	 * @var UserHasProjectManager
	 */
	protected $userHasProjectManager;

	/**
	 * Server manager
	 *
	 * @var ServerManager
	 */
	protected $serverManager;

	/**
	 * TaskType manager
	 *
	 * @var TaskTypeManager
	 */
	protected $taskTypeManager;

	/**
	 * ServerType manager
	 *
	 * @var ServerTypeManager
	 */
	protected $serverTypeManager;

	/**
	 * Task manager
	 *
	 * @var TaskManager
	 */
	protected $taskManager;

	/**
	 * TaskLog manager
	 *
	 * @var TaskLogManager
	 */
	protected $taskLogManager;

	/**
	 * Certificate manager
	 *
	 * @var CertificateManager
	 */
	protected $certificateManager;

	/**
	 * Order manager
	 *
	 * @var OrderManager
	 */
	protected $orderManager;

	/**
	 * ServerHasCertificate manager
	 *
	 * @var ServerHasCertificateManager
	 */
	protected $serverHasCertificateManager;

	/**
	 * API Client class constructor
	 *
	 * @param string $bearerToken Bearer authentication access token
	 * @param string $apiBaseUrl API base url for requests
	 * @param string[] $globalHeaders Map of global headers to use with every requests
	 * @param mixed[] $guzzleClientConfig Additional Guzzle client configuration
	 */
	public function __construct($bearerToken, $apiBaseUrl = 'https://www.ems-encrypt.com', $globalHeaders = [], $guzzleClientConfig = [])
	{
		$this->apiBaseUrl = $apiBaseUrl;
		$this->globalHeaders = $globalHeaders;

		$this->bearerToken = $bearerToken;

		$stack = new HandlerStack();
		$stack->setHandler(new CurlHandler());

		$stack->push(Middleware::mapRequest(function (RequestInterface $request) {
			if (count($this->globalHeaders) > 0) {
				$request = $request->withHeader('Authorization', 'Bearer ' . $this->bearerToken);
				foreach ($this->globalHeaders as $header => $value) {
					$request = $request->withHeader($header, $value);
				}
				return $request;
			} else {
				return $request->withHeader('Authorization', 'Bearer ' . $this->bearerToken);
			}
		}));
	
		$guzzleClientConfig['handler'] = $stack;
		$guzzleClientConfig['base_uri'] = $apiBaseUrl;

		$this->httpClient = new GuzzleClient($guzzleClientConfig);

		$this->meManager = new MeManager($this);
		$this->meNotificationManager = new MeNotificationManager($this);
		$this->userGroupManager = new UserGroupManager($this);
		$this->userManager = new UserManager($this);
		$this->i18nLangManager = new I18nLangManager($this);
		$this->userRoleManager = new UserRoleManager($this);
		$this->projectManager = new ProjectManager($this);
		$this->userHasProjectManager = new UserHasProjectManager($this);
		$this->serverManager = new ServerManager($this);
		$this->taskTypeManager = new TaskTypeManager($this);
		$this->serverTypeManager = new ServerTypeManager($this);
		$this->taskManager = new TaskManager($this);
		$this->taskLogManager = new TaskLogManager($this);
		$this->certificateManager = new CertificateManager($this);
		$this->orderManager = new OrderManager($this);
		$this->serverHasCertificateManager = new ServerHasCertificateManager($this);
	}

	/**
	 * Return the API base url
	 *
	 * @return string
	 */
	public function getApiBaseUrl()
	{
		return $this->apiBaseUrl;
	}

	/**
	 * Return the map of global headers to use with every requests
	 *
	 * @return string[]
	 */
	public function getGlobalHeaders()
	{
		return $this->globalHeaders;
	}

	/**
	 * Return the Guzzle HTTP client
	 *
	 * @return GuzzleClient
	 */
	public function getHttpClient()
	{
		return $this->httpClient;
	}

	/**
	 * Return the Me manager
	 *
	 * @return MeManager
	 */
	public function MeManager()
	{
		return $this->meManager;
	}
	
	/**
	 * Return the MeNotification manager
	 *
	 * @return MeNotificationManager
	 */
	public function MeNotificationManager()
	{
		return $this->meNotificationManager;
	}
	
	/**
	 * Return the UserGroup manager
	 *
	 * @return UserGroupManager
	 */
	public function UserGroupManager()
	{
		return $this->userGroupManager;
	}
	
	/**
	 * Return the User manager
	 *
	 * @return UserManager
	 */
	public function UserManager()
	{
		return $this->userManager;
	}
	
	/**
	 * Return the I18nLang manager
	 *
	 * @return I18nLangManager
	 */
	public function I18nLangManager()
	{
		return $this->i18nLangManager;
	}
	
	/**
	 * Return the UserRole manager
	 *
	 * @return UserRoleManager
	 */
	public function UserRoleManager()
	{
		return $this->userRoleManager;
	}
	
	/**
	 * Return the Project manager
	 *
	 * @return ProjectManager
	 */
	public function ProjectManager()
	{
		return $this->projectManager;
	}
	
	/**
	 * Return the UserHasProject manager
	 *
	 * @return UserHasProjectManager
	 */
	public function UserHasProjectManager()
	{
		return $this->userHasProjectManager;
	}
	
	/**
	 * Return the Server manager
	 *
	 * @return ServerManager
	 */
	public function ServerManager()
	{
		return $this->serverManager;
	}
	
	/**
	 * Return the TaskType manager
	 *
	 * @return TaskTypeManager
	 */
	public function TaskTypeManager()
	{
		return $this->taskTypeManager;
	}
	
	/**
	 * Return the ServerType manager
	 *
	 * @return ServerTypeManager
	 */
	public function ServerTypeManager()
	{
		return $this->serverTypeManager;
	}
	
	/**
	 * Return the Task manager
	 *
	 * @return TaskManager
	 */
	public function TaskManager()
	{
		return $this->taskManager;
	}
	
	/**
	 * Return the TaskLog manager
	 *
	 * @return TaskLogManager
	 */
	public function TaskLogManager()
	{
		return $this->taskLogManager;
	}
	
	/**
	 * Return the Certificate manager
	 *
	 * @return CertificateManager
	 */
	public function CertificateManager()
	{
		return $this->certificateManager;
	}
	
	/**
	 * Return the Order manager
	 *
	 * @return OrderManager
	 */
	public function OrderManager()
	{
		return $this->orderManager;
	}
	
	/**
	 * Return the ServerHasCertificate manager
	 *
	 * @return ServerHasCertificateManager
	 */
	public function ServerHasCertificateManager()
	{
		return $this->serverHasCertificateManager;
	}
}