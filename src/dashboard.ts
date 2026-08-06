import { createApp } from 'vue'
import Dashboard from './Dashboard.vue'

import './dashboard.css'

document.addEventListener('DOMContentLoaded', () => {
	OCA.Dashboard.register('externalportal', (el) => {
		const app = createApp(Dashboard)
		app.mount(el)
		return app
	})
})
