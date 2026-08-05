<template>
	<div id="externalportal_prefs">
		<NcSettingsSection :name="t('externalportal', 'External portal settings')">
			<div class="settings-layout">
				<div class="settings-form">
					<NcTextField id="externalportal-widget-title"
						:modelValue="state.widgetTitle"
						:label="t('externalportal', 'Widget title')"
						:placeholder="t('externalportal', 'External portal')"
						:loading="saving && lastChanged === 'widgetTitle'"
						:success="saved && lastChanged === 'widgetTitle'"
						@update:modelValue="updateField('widgetTitle', String($event))" />
					<NcCheckboxRadioSwitch :modelValue="!!state.extraWide"
						type="switch"
						@update:modelValue="updateField('extraWide', $event)">
						{{ t('externalportal', 'Extra wide') }}
					</NcCheckboxRadioSwitch>
					<p class="setting-hint">
						{{ t('externalportal', 'Expand the widget from 320px to 400px when there are more than 6 links') }}
					</p>
					<NcCheckboxRadioSwitch :modelValue="!!state.maxSize"
						type="switch"
						@update:modelValue="updateField('maxSize', $event)">
						{{ t('externalportal', 'Limit icon size') }}
					</NcCheckboxRadioSwitch>
					<p class="setting-hint">
						{{ t('externalportal', 'Limit each icon to 64x64px to prevent small pixmap icons from being stretched') }}
					</p>
					<NcCheckboxRadioSwitch :modelValue="!!state.showFiles"
						type="switch"
						@update:modelValue="updateField('showFiles', $event)">
						{{ t('externalportal', 'Show Files link') }}
					</NcCheckboxRadioSwitch>
					<p class="setting-hint">
						{{ t('externalportal', 'Add a shortcut to the Files app as the first item in the widget') }}
					</p>
					<NcSelect v-model="state.iconColorMode"
						:options="iconColorOptions"
						:reduce="(option: { id: string, label: string }) => option.id"
						label="label"
						:inputLabel="t('externalportal', 'Icon Colors')"
						@option:selected="lastChanged = 'iconColorMode'" />
					<div v-if="state.iconColorMode === 'CUSTOM'">
						<ColorInputField v-model="state.customIconColor"
							:label="t('externalportal', 'Custom icon color')" />
					</div>
				</div>
				<div class="settings-preview">
					<h3>
						{{ t('externalportal', 'Preview') }}
					</h3>
					<div class="panel-wrapper">
						<div class="panel">
							<div class="panel--header">
								<h2>
									<div aria-labelledby="panel--header--icon--description"
										aria-hidden="true"
										class="icon-externalportal"
										role="img" />
									{{ state.widgetTitle || t('externalportal', 'External portal') }}
								</h2>
								<span id="panel--header--icon--description" class="hidden-visually">
									{{ t('dashboard', '"{title} icon"', {title: state.widgetTitle || t('externalportal', 'External portal')}) }}
								</span>
							</div>
							<div class="panel--content">
								<Dashboard ref="dashboard" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</NcSettingsSection>
	</div>
</template>

<script lang="ts">
import axios from '@nextcloud/axios'
import { showError } from '@nextcloud/dialogs'
import { loadState } from '@nextcloud/initial-state'
import { translate as t } from '@nextcloud/l10n'
import { generateUrl } from '@nextcloud/router'
import NcCheckboxRadioSwitch from '@nextcloud/vue/components/NcCheckboxRadioSwitch'
import NcSelect from '@nextcloud/vue/components/NcSelect'
import NcSettingsSection from '@nextcloud/vue/components/NcSettingsSection'
import NcTextField from '@nextcloud/vue/components/NcTextField'
import ColorInputField from './ColorInputField.vue'
import Dashboard from './Dashboard.vue'

interface AdminConfig {
	widgetTitle: string
	extraWide: string | boolean
	maxSize: string | boolean
	showFiles: string | boolean
	iconColorMode: string
	customIconColor: string
	[key: string]: string | boolean
}

export default {
	name: 'AdminSettings',
	components: {
		NcCheckboxRadioSwitch,
		NcSelect,
		NcSettingsSection,
		NcTextField,
		Dashboard,
		ColorInputField,
	},

	data() {
		return {
			state: loadState<AdminConfig>('externalportal', 'admin-config'),
			saving: false,
			saved: false,
			lastChanged: null as string | null,
			saveTimeout: undefined as ReturnType<typeof setTimeout> | undefined,
			savedTimeout: undefined as ReturnType<typeof setTimeout> | undefined,
			iconColorOptions: [
				{ id: 'THEMING', label: t('externalportal', 'Use theme color') },
				{ id: 'PRIMARY', label: t('externalportal', 'Use text color') },
				{ id: 'DEFAULT', label: t('externalportal', 'Use icons as-is') },
				{ id: 'CUSTOM', label: t('externalportal', 'Use custom color') },
			],
		}
	},

	watch: {
		state: {
			deep: true,
			handler() {
				clearTimeout(this.saveTimeout)
				this.saveTimeout = setTimeout(() => {
					this.saveSettings()
				}, 500)
			},
		},
	},

	methods: {
		t,
		updateField(field: string, value: string | boolean) {
			this.lastChanged = field
			this.state[field] = value
		},

		async saveSettings() {
			this.saving = true
			this.saved = false
			const values = {
				widgetTitle: this.state.widgetTitle,
				maxSize: this.state.maxSize ? '1' : '',
				extraWide: this.state.extraWide ? '1' : '',
				showFiles: this.state.showFiles ? '1' : '',
				iconColorMode: this.state.iconColorMode,
				customIconColor: this.state.customIconColor,
			}
			const url = generateUrl('/apps/externalportal/admin-config')
			try {
				await axios.put(url, { values })
				this.saved = true
				clearTimeout(this.savedTimeout)
				this.savedTimeout = setTimeout(() => {
					this.saved = false
				}, 3000)
			} catch (e: unknown) {
				const message = e instanceof Error ? e.message : ''
				showError(t('externalportal', 'Failed to save external portal options')
					+ (message ? `: ${message}` : ''))
				console.error(e)
			}
			this.saving = false
			await (this.$refs.dashboard as InstanceType<typeof Dashboard>).reload()
		},
	},
}
</script>

<style scoped>
.settings-layout {
	display: flex;
	flex-wrap: wrap;
	gap: 32px;
}

.settings-form {
	flex: 1;
	min-width: 300px;
}

.settings-preview {
	flex-shrink: 0;
}

.setting-hint {
	margin-top: -4px;
	margin-bottom: 8px;
	color: var(--color-text-maxcontrast);
	font-size: small;
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
	border-radius: var(--border-radius-large);
}

.panel.externalportal--extra-wide {
	width: 400px;
}

.panel--header {
	padding: 16px;
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
	margin-inline-end: 16px;
	background-position: center;
	float: inline-start;
}
</style>
