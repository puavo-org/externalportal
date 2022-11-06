// SPDX-FileCopyrightText: Tuomas Nurmi <dev@opinsys.fi>
// SPDX-License-Identifier: AGPL-3.0-or-later

import Vue from 'vue'
import Vuex from 'vuex'

import Dashboard from './Dashboard.vue'

Vue.use(Vuex)

Vue.prototype.$OC = OC
Vue.prototype.$OCA = OCA
Vue.prototype.$appVersion = '0.0.1'

document.addEventListener('DOMContentLoaded', () => {
	OCA.Dashboard.register('externalportal', (el) => {
		const View = Vue.extend(Dashboard)
		const vm = new View({
			propsData: {},
		}).$mount(el)
		return vm
	})
})
