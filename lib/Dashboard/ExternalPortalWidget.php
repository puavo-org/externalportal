<?php
// Copyright (C) 2026 Opinsys Oy <dev@opinsys.fi>
// SPDX-FileCopyrightText: Opinsys Oy <dev@opinsys.fi>
// SPDX-License-Identifier: AGPL-3.0-or-later

declare(strict_types=1);

namespace OCA\ExternalPortal\Dashboard;

use OCA\ExternalPortal\AppInfo\Application;
use OCP\Dashboard\IWidget;
use OCP\IConfig;
use OCP\IL10N;

//boilerplate structure from https://docs.nextcloud.com/server/latest/developer_manual/digging_deeper/dashboard.html
class ExternalPortalWidget implements IWidget
{
  private IL10N $l10n;
  private IConfig $config;

  public function __construct(IL10N $l10n, IConfig $config)
  {
    $this->l10n = $l10n;
    $this->config = $config;
  }

  /**
   * @return string Unique id that identifies the widget, e.g. the app id
   */
  public function getId(): string
  {
    return "externalportal";
  }

  /**
   * @return string User facing title of the widget
   */
  public function getTitle(): string
  {
    $widgetTitle = $this->config->getAppValue(
      Application::APP_ID,
      "widgetTitle",
      "External portal",
    );
    return $widgetTitle ?: "External portal";
  }

  /**
   * @return int Initial order for widget sorting
   *   in the range of 10-100, 0-9 are reserved for shipped apps
   */
  public function getOrder(): int
  {
    return 0;
  }

  /**
   * @return string css class that displays an icon next to the widget title
   */
  public function getIconClass(): string
  {
    return "icon-externalportal";
  }

  /**
   * @return string|null The absolute url to the apps own view
   */
  public function getUrl(): ?string
  {
    return null;
  }

  /**
   * Execute widget bootstrap code like loading scripts and providing initial state
   */
  public function load(): void
  {
    \OCP\Util::addScript("externalportal", "externalportal-dashboard");
  }
}
