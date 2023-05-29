// SPDX-FileCopyrightText: Opinsys Oy <dev@opinsys.fi>
// SPDX-License-Identifier: AGPL-3.0-or-later

import Vue from 'vue'
import { translate as t, translatePlural as n } from '@nextcloud/l10n'

import AdminSettings from './AdminSettings.vue'

// Adding translations to the whole app
Vue.mixin({
	methods: {
		t,
		n,
	},
})

Vue.prototype.$OC = OC
Vue.prototype.$OCA = OCA
Vue.prototype.$appVersion = '1.2.0'

export default new Vue({
	el: '#externalportal_prefs',
	render: h => h(AdminSettings),
})
