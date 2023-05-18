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
		<div class="settings">
			<h2>
				External portal settings
			</h2>
			<div class="setting">
				<label for="externaportal-widget-title">
					<span class="icon icon-file"/>
					Widget title
				</label>
				<input id="externaportal-widget-title"
					   v-model="state.widgetTitle"
					   type="text"
					   :class="{ 'icon-loading-small': saving }"
					   :placeholder="('External portal')">
			</div>
			<div class="setting">
				<label for="extraWide">Make the widget wider when there are many links</label>
				<input v-model="state.extraWide" type="checkbox" name="extraWide">
			</div>
			<div class="setting">
				<label for="maxSize">Limit maximum icon size to to avoid over-stretching 32x32 pixmap icons</label>
				<input v-model="state.maxSize" type="checkbox" name="maxSize">
			</div>
			<div class="setting">
				<label for="showFiles">Show Files link for everyone</label>
				<input v-model="state.showFiles" type="checkbox" name="showFiles">
			</div>
			<div class="setting">
				<button color="primary" @click="saveSettings">
					Save
				</button>
			</div>
		</div>
		<div class="right-pane">
			<h2>Preview</h2>
			<div class="panel-wrapper">
				<div class="panel">
					<div class="panel--header">
						<h2>
							<div aria-labelledby="panel--header--icon--description"
								 aria-hidden="true"
								 class="icon-externalportal"
								 role="img"/>
							{{ this.state.widgetTitle || "External Portal" }}
						</h2>
						<span id="panel--header--icon--description" class="hidden-visually">
							{{ t('dashboard', '"{title} icon"', {title: this.state.widgetTitle || "External Portal"}) }}
						</span>
					</div>
					<div class="panel--content">
						<Dashboard ref="dashboard" :title="this.state.widgetTitle"></Dashboard>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import {loadState} from '@nextcloud/initial-state'
import axios from '@nextcloud/axios'
import {generateUrl} from '@nextcloud/router'
import {showSuccess, showError} from '@nextcloud/dialogs'
import Dashboard from "./Dashboard.vue";

export default {
	name: 'AdminSettings',
	components: {
		Dashboard
	},
	props: [],
	data() {
		return {
			state: loadState('externalportal', 'admin-config'),
			saving: false,
		}
	},
	methods: {
		async saveSettings() {
			this.saving = true
			const values = {
				widgetTitle: this.state.widgetTitle,
				maxSize: this.state.maxSize,
				extraWide: this.state.extraWide,
				showFiles: this.state.showFiles,
			}
			const req = {
				values,
			}
			const url = generateUrl('/apps/externalportal/admin-config')
			console.debug('"' + JSON.stringify(req) + '"')
			try {
				await axios.put(url, req)
			} catch (e) {
				showError(t('externalportal', 'Failed to save external portal options') + `: ${e.response?.request?.responseText ?? ''}`)
				console.debug(e)
			}
			showSuccess(t('externalportal', 'External portal options saved'))
			this.saving = false
			await this.$refs.dashboard.reload()
		}
	},
}
</script>

<style scoped>
#externalportal_prefs {
	display: flex;
	flex-direction: row;
	justify-content: space-evenly;
	flex-wrap: wrap;
}

.settings {
	display: flex;
	flex-direction: column;
	flex-shrink: 0;
}

.right-pane {
	margin: 16px;
}

.panel-wrapper {
	background-color: var(--color-background-plain, var(--color-main-background));
	padding: 16px;
}

.panel {
	width: 320px;
	max-width: 100%;
	background-color: var(--color-main-background-blur);
	box-sizing: border-box;
	backdrop-filter: var(--filter-background-blur);
	-webkit-backdrop-filter: var(--filter-background-blur);
	border-radius: var(--border-radius-large);
}

.panel--header {
	padding: 16px;
	cursor: grab;
}

.panel--content {
	margin: 0 16px 16px 16px;
	height: 424px;
	overflow: visible;
	box-sizing: border-box;
}

.icon-externalportal {
	background-image: url('../img/externalportal-dark.svg');
	filter: var(--background-invert-if-dark);
	background-size: 32px;
	width: 32px;
	height: 32px;
	margin-right: 16px;
	background-position: center;
	float: left;
}

</style>
