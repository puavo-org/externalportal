<?php
declare(strict_types=1);
// SPDX-FileCopyrightText: Tuomas Nurmi <dev@opinsys.fi>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\ExternalPortal\AppInfo;

use OCA\External\Dashboard\ExternalPortalWidget;
use OCP\AppFramework\App;

class Application extends App {
  public const APP_ID = 'externalportal';
  
  public function __construct() {
    parent::__construct(self::APP_ID);
  }
  
  public function register(IRegistrationContext $context): void {
    $context->registerDashboardWidget(ExternalPortalWidget::class);
  }
}
