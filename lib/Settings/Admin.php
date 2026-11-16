<?php

declare(strict_types=1);

namespace OCA\ExternalPortal\Settings;

use OCA\ExternalPortal\AppInfo\Application;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\IAppConfig;
use OCP\Settings\ISettings;

/** @psalm-suppress UnusedClass */
class Admin implements ISettings {
	private IAppConfig $appConfig;
	private IInitialState $initialStateService;

	public function __construct(
		IAppConfig $appConfig,
		IInitialState $initialStateService,
	) {
		$this->appConfig = $appConfig;
		$this->initialStateService = $initialStateService;
	}

	public function getForm(): TemplateResponse {
		$adminConfig = [
			'widgetTitle' => $this->appConfig->getValueString(Application::APP_ID, 'widgetTitle', ''),
			'extraWide' => $this->appConfig->getValueString(Application::APP_ID, 'extraWide', ''),
			'iconSize' => $this->appConfig->getValueString(Application::APP_ID, 'iconSize', 'medium'),
			'showFiles' => $this->appConfig->getValueString(Application::APP_ID, 'showFiles', ''),
			'iconColorMode' => $this->appConfig->getValueString(Application::APP_ID, 'iconColorMode', 'DEFAULT'),
			'customIconColor' => $this->appConfig->getValueString(Application::APP_ID, 'customIconColor', ''),
		];
		$this->initialStateService->provideInitialState('admin-config', $adminConfig);
		return new TemplateResponse(Application::APP_ID, 'adminSettings');
	}

	public function getSection(): string {
		return 'externalportal';
	}

	public function getPriority(): int {
		return 10;
	}
}
