<?php

declare(strict_types=1);

namespace OCA\ExternalPortal\Dashboard;

use OCA\ExternalPortal\AppInfo\Application;
use OCP\Dashboard\IWidget;
use OCP\IAppConfig;
use OCP\IL10N;

class ExternalPortalWidget implements IWidget {
	private IAppConfig $appConfig;
	private IL10N $l;

	/** @psalm-suppress PossiblyUnusedMethod */
	public function __construct(IAppConfig $appConfig, IL10N $l) {
		$this->appConfig = $appConfig;
		$this->l = $l;
	}

	/**
	 * @return string Unique id that identifies the widget, e.g. the app id
	 */
	public function getId(): string {
		return 'externalportal';
	}

	/**
	 * @return string User facing title of the widget
	 */
	public function getTitle(): string {
		$widgetTitle = $this->appConfig->getValueString(
			Application::APP_ID,
			'widgetTitle',
			'',
		);
		return $widgetTitle ?: $this->l->t('External portal');
	}

	/**
	 * @return int Initial order for widget sorting
	 *             in the range of 10-100, 0-9 are reserved for shipped apps
	 */
	public function getOrder(): int {
		return 0;
	}

	/**
	 * @return string css class that displays an icon next to the widget title
	 */
	public function getIconClass(): string {
		return 'icon-externalportal';
	}

	/**
	 * @return string|null The absolute url to the apps own view
	 */
	public function getUrl(): ?string {
		return null;
	}

	/**
	 * Execute widget bootstrap code like loading scripts and providing initial state
	 */
	public function load(): void {
		\OCP\Util::addScript('externalportal', 'externalportal-dashboard');
		\OCP\Util::addStyle('externalportal', 'externalportal-dashboard');

		// The widget labels the optional shortcut with the Files app's own
		// translation of its name, but that catalog is not loaded on the
		// dashboard by default.
		$showFiles = $this->appConfig->getValueString(
			Application::APP_ID,
			'showFiles',
			'',
		);
		if ($showFiles !== '') {
			\OCP\Util::addTranslations('files');
		}
	}
}
