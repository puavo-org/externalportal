<?php

declare(strict_types=1);

namespace OCA\ExternalPortal\Controller;

use OCA\ExternalPortal\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\IAppConfig;
use OCP\IRequest;

/** @psalm-suppress UnusedClass */
class ConfigController extends Controller {
	private IAppConfig $appConfig;

	public function __construct(
		string $AppName,
		IRequest $request,
		IAppConfig $appConfig,
	) {
		parent::__construct($AppName, $request);
		$this->appConfig = $appConfig;
	}

	private const ALLOWED_KEYS = [
		'widgetTitle',
		'extraWide',
		'maxSize',
		'showFiles',
		'iconColorMode',
		'customIconColor',
	];

	/**
	 * @param array<string, string> $values
	 */
	public function setAdminConfig(array $values): DataResponse {
		foreach ($values as $key => $value) {
			if (in_array($key, self::ALLOWED_KEYS, true)) {
				$this->appConfig->setValueString(Application::APP_ID, $key, $value);
			}
		}
		return new DataResponse(1);
	}

	#[NoAdminRequired]
	public function getConfig(): DataResponse {
		$extraWide = $this->appConfig->getValueString(Application::APP_ID, 'extraWide', '');
		$maxSize = $this->appConfig->getValueString(Application::APP_ID, 'maxSize', '');
		$showFiles = $this->appConfig->getValueString(Application::APP_ID, 'showFiles', '');
		$iconColorMode = $this->appConfig->getValueString(Application::APP_ID, 'iconColorMode', 'DEFAULT');
		$customIconColor = $this->appConfig->getValueString(Application::APP_ID, 'customIconColor', '');
		return new DataResponse([
			'extraWide' => $extraWide,
			'showFiles' => $showFiles,
			'maxSize' => $maxSize,
			'iconColorMode' => $iconColorMode,
			'customIconColor' => $customIconColor,
		]);
	}
}
