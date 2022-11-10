<template>
	<div id="externalportal_prefs" class="section">
		<h2>
			Hello
		</h2>
		
                        <label for="externaportal-widget-title">
                        <span class="icon icon-file" />
                        Widget title
			</label>
			<input id="externaportal-widget-title"
				v-model="state.widgetTitle"
				type="text"
				:class="{ 'icon-loading-small': saving }"
				:placeholder="('External portal')"
				@input="onTextChange">
	</div>
</template>

<script>
import { loadState } from '@nextcloud/initial-state'
import axios from '@nextcloud/axios'
import { getCurrentUser } from '@nextcloud/auth'
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
        onTextChange() {
			/*	this.saveOptions({
					widgetTitle: this.state.widgetTitle,
				})*/
		},
}
</script>
