<?php

namespace OCA\ExternalPortal\Settings;

use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Settings\IIconSection;

class Section implements IIconSection {
	private IL10N $l;
	private IURLGenerator $url;

	public function __construct(IURLGenerator $url, IL10N $l) {
		$this->url = $url;
		$this->l = $l;
	}

	public function getIcon(): string {
		return $this->url->imagePath('external', 'external.svg');
	}

	public function getID(): string {
		return 'externalportal';
	}

	public function getName(): string {
		return $this->l->t('External portal');
	}

	public function getPriority(): int {
		return 55;
	}
}
