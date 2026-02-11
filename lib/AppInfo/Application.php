<?php
// Copyright (C) 2022 Opinsys Oy <dev@opinsys.fi>
// SPDX-FileCopyrightText: Opinsys Oy <dev@opinsys.fi>
// SPDX-License-Identifier: AGPL-3.0-or-later

declare(strict_types=1);

namespace OCA\ExternalPortal\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCA\ExternalPortal\Dashboard\ExternalPortalWidget;

class Application extends App implements IBootstrap
{
  public const APP_ID = "externalportal";

  public function __construct()
  {
    parent::__construct(self::APP_ID);
  }

  public function register(IRegistrationContext $context): void
  {
    $context->registerDashboardWidget(ExternalPortalWidget::class);
  }
  public function boot(IBootContext $context): void {}
}
