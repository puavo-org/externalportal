
import Vue from 'vue'
import { translate as t, translatePlural as n } from '@nextcloud/l10n'

import AdminSettings from './AdminSettings'

// Adding translations to the whole app
Vue.mixin({
	methods: {
		t,
		n,
	},
})

export default new Vue({
	el: '#externalportal_prefs',
	render: h => h(AdminSettings),
})
