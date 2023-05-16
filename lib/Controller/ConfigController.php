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
 * SPDX-FileCopyrightText: Opinsys Oy <dev@opinsys.fi>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 *
 */

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
		$maxSize = $this->config->getAppValue(Application::APP_ID, 'maxSize', false);
		$showFiles = $this->config->getAppValue(Application::APP_ID, 'showFiles', false);
		$iconColorMode = $this->config->getAppValue(Application::APP_ID, 'iconColorMode', "DEFAULT");
		$customIconColor = $this->config->getAppValue(Application::APP_ID, 'customIconColor', '');
		return new DataResponse([
			'extraWide' => $extraWide,
			'showFiles' => $showFiles,
			'maxSize' => $maxSize,
			'iconColorMode' => $iconColorMode,
			'customIconColor' => $customIconColor]);
	}

}
