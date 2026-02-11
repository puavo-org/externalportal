// SPDX-FileCopyrightText: Opinsys Oy <dev@opinsys.fi>
// SPDX-License-Identifier: AGPL-3.0-or-later

import { createApp } from 'vue'

import Dashboard from './Dashboard.vue'

import '../css/dashboard.scss'

document.addEventListener('DOMContentLoaded', () => {
	OCA.Dashboard.register('externalportal', (el) => {
		const app = createApp(Dashboard)
		app.mount(el)
		return app
	})
})
