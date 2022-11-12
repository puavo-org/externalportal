<?php

/**
 * @copyright Copyright (c) 2022 Opinsys Oy <dev@opinsys.fi>
 *
 * @author Tuomas Nurmi <tuomas.nurmi@opinsys.fi>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
SPDX-FileCopyrightText: Opinsys Oy <dev@opinsys.fi>
SPDX-License-Identifier: AGPL-3.0-or-later
 *
 */
 
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
		$extraWide = $this->config->getAppValue(Application::APP_ID, 'extraWide', false);

		$adminConfig = [
			'widgetTitle' => $widgetTitle,
			'extraWide' => $extraWide,
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
