<?php
namespace OCA\ExternalPortal\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCP\IL10N;
use OCP\IConfig;
use OCP\Settings\ISettings;
use OCP\IURLGenerator;
use OCP\AppFramework\Services\IInitialState;

use OCA\ExternalPortal\AppInfo\Application;

class Admin implements ISettings {



        /** @var IConfig */
        private $config;

        /** @var IL10N */
        private $l;


        /**
         * Admin constructor.
         *
         * @param IConfig $config
         * @param IL10N $l
         */
        public function __construct(IConfig $config,
                                                                IL10N $l,
				    IInitialState $initialStateService
        ) {
                $this->config = $config;
                $this->l = $l;
		$this->initialStateService = $initialStateService;
        }
	/**
	 * @return TemplateResponse
	 */
	public function getForm(): TemplateResponse {
		$widgetTitle = $this->config->getAppValue(Application::APP_ID, 'widgetTitle', '');

		$adminConfig = [
			'widgetTitle' => $widgetTitle,
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
