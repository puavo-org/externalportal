<?php

declare(strict_types=1);
// SPDX-FileCopyrightText: Opinsys Oy <dev@opinsys.fi>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\ExternalPortal\Tests\Unit\Dashboard;

use OCA\ExternalPortal\AppInfo\Application;
use OCA\ExternalPortal\Dashboard\ExternalPortalWidget;
use OCP\IConfig;
use OCP\IL10N;
use PHPUnit\Framework\TestCase;

class ExternalPortalWidgetTest extends TestCase
{
    private ExternalPortalWidget $widget;
    private IConfig $config;

    protected function setUp(): void
    {
        $this->config = $this->createMock(IConfig::class);
        $l10n = $this->createMock(IL10N::class);
        $this->widget = new ExternalPortalWidget($l10n, $this->config);
    }

    public function testGetId(): void
    {
        $this->assertEquals('externalportal', $this->widget->getId());
    }

    public function testGetTitleDefault(): void
    {
        $this->config->method('getAppValue')
            ->with(Application::APP_ID, 'widgetTitle', 'External portal')
            ->willReturn('');

        $this->assertEquals('External portal', $this->widget->getTitle());
    }

    public function testGetTitleCustom(): void
    {
        $this->config->method('getAppValue')
            ->with(Application::APP_ID, 'widgetTitle', 'External portal')
            ->willReturn('My Portal');

        $this->assertEquals('My Portal', $this->widget->getTitle());
    }

    public function testGetOrder(): void
    {
        $this->assertEquals(0, $this->widget->getOrder());
    }

    public function testGetIconClass(): void
    {
        $this->assertEquals('icon-externalportal', $this->widget->getIconClass());
    }

    public function testGetUrl(): void
    {
        $this->assertNull($this->widget->getUrl());
    }
}
