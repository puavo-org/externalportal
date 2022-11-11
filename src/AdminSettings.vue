<template>
	<div id="externalportal_prefs" class="section">
		<h2>
			External portal settings
		</h2>
		
                        <label for="externaportal-widget-title">
                        <span class="icon icon-file" />
                        Widget title
			</label>
			<input id="externaportal-widget-title"
				v-model="state.widgetTitle"
				type="text"
				:class="{ 'icon-loading-small': saving }"
				:placeholder="('External portal')">
				<br>
        <label for="extraWide">Make the widget wider when there are many links</label>
				<input v-model="state.extraWide" type="checkbox" name="extraWide" />
<br>
				<button color="primary" @click="saveSettings">Save</button>
	</div>
</template>

<script>
import { loadState } from '@nextcloud/initial-state'
import axios from '@nextcloud/axios'
import { generateUrl, generateOcsUrl } from '@nextcloud/router'
import { getCurrentUser } from '@nextcloud/auth'
import { showSuccess, showError } from '@nextcloud/dialogs'
export default {
	name: 'AdminSettings',
	components: {
	},
	props: [],
	data() {
		return {
			state: loadState('externalportal', 'admin-config'),
			saving: false,
			currentUser: getCurrentUser(),
		}
	},
	methods: {
      saveSettings() {
			this.saving = true
			let values={
        widgetTitle: this.state.widgetTitle,
        extraWide: this.state.extraWide,
			}
			const req = {
				values,
			}
			const url = generateUrl('/apps/externalportal/admin-config')
      console.debug('"' + JSON.stringify(req) + '"')
			axios.put(url, req)
				.then((response) => {
					showSuccess(t('externalportal', 'External portal options saved'))
				})
				.catch((error) => {
					showError(
						t('externalportal', 'Failed to save external portal  options')
						+ ': ' + (error.response?.request?.responseText ?? '')
					)
					console.debug(error)
				})
				.then(() => {
					this.saving = false
				})
		},
  }
		
}
</script>
