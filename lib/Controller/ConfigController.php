<?php


namespace OCA\ExternalPortal\Controller;

use OCP\Files\IAppData;
use OCP\AppFramework\Http\DataDisplayResponse;

use OCP\IConfig;
use OCP\IServerContainer;

use OCP\AppFramework\Http;
use OCP\AppFramework\Http\RedirectResponse;

use OCP\Files\IRootFolder;
use OCP\IUserManager;
use OCP\Files\FileInfo;


use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

use OCA\ExternalPortal\AppInfo\Application;


class ConfigController extends Controller {
	private $config;
	private $dbtype;

	public function __construct($AppName,
								IRequest $request,
								IServerContainer $serverContainer,
								IConfig $config,
								IAppData $appData,
								?string $userId) {
		parent::__construct($AppName, $request);
		$this->userId = $userId;
		$this->appData = $appData;
		$this->serverContainer = $serverContainer;
		$this->config = $config;
	}

	public function setAdminConfig(array $values): DataResponse {
		foreach ($values as $key => $value) {
			$this->config->setAppValue(Application::APP_ID, $key, $value);
		}
		return new DataResponse(1);
	}
	
  /**
	 * @NoAdminRequired
	 */
	public function getConfig(): DataResponse {
	
		$extraWide = $this->config->getAppValue(Application::APP_ID, 'extraWide', false);
		return new DataResponse(['extraWide' => $extraWide]);
	}
	
	}
