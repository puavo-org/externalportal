<!--
Nextcloud - External Portal Dashboard
@author Tuomas Nurmi
@copyright 2022 Opinsys Oy <dev@opinsys.fi>
This library is free software; you can redistribute it and/or
modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
License as published by the Free Software Foundation; either
version 3 of the License, or any later version.
This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU AFFERO GENERAL PUBLIC LICENSE for more details.
You should have received a copy of the GNU Affero General Public
License along with this library. If not, see <http://www.gnu.org/licenses/>.

The Welcome widget ( https://github.com/julien-nc/welcome ) has been a very useful
guiding source for basic dashboard widget and configuration functionality.

SPDX-FileCopyrightText: Opinsys Oy <dev@opinsys.fi>
SPDX-License-Identifier: AGPL-3.0-or-later
-->

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
		<input v-model="state.extraWide" type="checkbox" name="extraWide">
		<br>
		<button color="primary" @click="saveSettings">
			Save
		</button>
	</div>
</template>

<script>
import { loadState } from '@nextcloud/initial-state'
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
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
		}
	},
	methods: {
		saveSettings() {
			this.saving = true
			const values = {
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
						t('externalportal', 'Failed to save external portal options')
						+ ': ' + (error.response?.request?.responseText ?? '')
					)
					console.debug(error)
				})
				.then(() => {
					this.saving = false
				})
		},
	},
}
</script>
