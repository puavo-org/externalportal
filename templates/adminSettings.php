<?php

declare(strict_types=1);

use OCP\Util;

$appId = OCA\ExternalPortal\AppInfo\Application::APP_ID;
Util::addScript($appId, $appId . '-admin');
Util::addStyle($appId, $appId . '-admin');

// The settings page renders a live preview of the widget, which can label the
// optional shortcut with the Files app's own translation of its name.
Util::addTranslations('files');
?>

<div id="externalportal_prefs"></div>
