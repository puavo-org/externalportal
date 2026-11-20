<template>
	<NcSettingsSection :name="t('externalportal', 'External portal')"
		:description="t('externalportal', 'A dashboard widget that shows the sites from the External sites app as app icons.')">
		<div class="externalportal-settings">
			<div class="externalportal-settings__form">
				<NcTextField id="externalportal-widget-title"
					:modelValue="state.widgetTitle"
					:label="t('externalportal', 'Widget title')"
					:placeholder="t('externalportal', 'External portal')"
					:helperText="t('externalportal', 'Leave empty to use the default title.')"
					:loading="saving && lastChanged === 'widgetTitle'"
					:success="saved && lastChanged === 'widgetTitle'"
					@update:modelValue="updateField('widgetTitle', String($event))" />

				<NcFormGroup :label="t('externalportal', 'Content')">
					<NcFormBox>
						<NcFormBoxSwitch :modelValue="!!state.showFiles"
							:label="t('externalportal', 'Show Files link')"
							:description="t('externalportal', 'Add a shortcut to the Files app as the first item in the widget')"
							@update:modelValue="updateField('showFiles', $event)" />
						<NcFormBoxSwitch :modelValue="!!state.extraWide"
							:label="t('externalportal', 'Extra wide')"
							:description="t('externalportal', 'Show five icons per row instead of four when there are more than 6 links')"
							@update:modelValue="updateField('extraWide', $event)" />
					</NcFormBox>
				</NcFormGroup>

				<NcRadioGroup :modelValue="state.iconSize || 'medium'"
					class="externalportal-settings__size"
					:label="t('externalportal', 'Icon size')"
					@update:modelValue="updateField('iconSize', $event)">
					<NcRadioGroupButton value="small" :label="t('externalportal', 'Small')" />
					<NcRadioGroupButton value="medium" :label="t('externalportal', 'Medium')" />
					<NcRadioGroupButton value="large" :label="t('externalportal', 'Large')" />
				</NcRadioGroup>

				<NcRadioGroup :modelValue="state.iconColorMode"
					:label="t('externalportal', 'Icon style')"
					@update:modelValue="updateField('iconColorMode', $event)">
					<NcCheckboxRadioSwitch value="THEMING">
						{{ t('externalportal', 'Theme color') }}
						<template #description>
							{{ t('externalportal', 'Tinted like the apps in the app menu') }}
						</template>
					</NcCheckboxRadioSwitch>
					<NcCheckboxRadioSwitch value="PRIMARY">
						{{ t('externalportal', 'Monochrome') }}
						<template #description>
							{{ t('externalportal', 'Icons in the text color') }}
						</template>
					</NcCheckboxRadioSwitch>
					<NcCheckboxRadioSwitch value="CUSTOM">
						{{ t('externalportal', 'Custom color') }}
						<template #description>
							{{ t('externalportal', 'Tinted with a color of your choice') }}
						</template>
					</NcCheckboxRadioSwitch>
					<NcCheckboxRadioSwitch value="DEFAULT">
						{{ t('externalportal', 'Original icons') }}
						<template #description>
							{{ t('externalportal', 'Keep the colors of multicolor logos') }}
						</template>
					</NcCheckboxRadioSwitch>
				</NcRadioGroup>

				<div v-if="state.iconColorMode === 'CUSTOM'" class="externalportal-settings__color">
					<NcColorPicker :modelValue="state.customIconColor || defaultCustomColor"
						advancedFields
						@update:modelValue="updateField('customIconColor', $event ?? '')">
						<NcButton>
							<template #icon>
								<span class="externalportal-settings__swatch"
									:style="{ backgroundColor: state.customIconColor || defaultCustomColor }" />
							</template>
							{{ t('externalportal', 'Choose icon color') }}
						</NcButton>
					</NcColorPicker>
				</div>
			</div>

			<div class="externalportal-settings__preview">
				<h3 id="externalportal-preview-heading">
					{{ t('externalportal', 'Preview') }}
				</h3>
				<!-- Mirrors the panel markup and styles of the dashboard app. -->
				<div class="preview-backdrop" aria-labelledby="externalportal-preview-heading" role="region">
					<div class="panel">
						<div class="panel--header">
							<h2>
								<img :src="iconUrl" :alt="previewIconLabel">
								{{ previewTitle }}
							</h2>
						</div>
						<div class="panel--content">
							<Dashboard ref="dashboard" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</NcSettingsSection>
</template>

<script lang="ts">
import axios from '@nextcloud/axios'
import { showError } from '@nextcloud/dialogs'
import { loadState } from '@nextcloud/initial-state'
import { translate as t } from '@nextcloud/l10n'
import { generateUrl, imagePath } from '@nextcloud/router'
import NcButton from '@nextcloud/vue/components/NcButton'
import NcCheckboxRadioSwitch from '@nextcloud/vue/components/NcCheckboxRadioSwitch'
import NcColorPicker from '@nextcloud/vue/components/NcColorPicker'
import NcFormBox from '@nextcloud/vue/components/NcFormBox'
import NcFormBoxSwitch from '@nextcloud/vue/components/NcFormBoxSwitch'
import NcFormGroup from '@nextcloud/vue/components/NcFormGroup'
import NcRadioGroup from '@nextcloud/vue/components/NcRadioGroup'
import NcRadioGroupButton from '@nextcloud/vue/components/NcRadioGroupButton'
import NcSettingsSection from '@nextcloud/vue/components/NcSettingsSection'
import NcTextField from '@nextcloud/vue/components/NcTextField'
import Dashboard from './Dashboard.vue'

interface AdminConfig {
	widgetTitle: string
	extraWide: string | boolean
	showFiles: string | boolean
	iconSize: string
	iconColorMode: string
	customIconColor: string
	[key: string]: string | boolean
}

export default {
	name: 'AdminSettings',
	components: {
		NcButton,
		NcCheckboxRadioSwitch,
		NcColorPicker,
		NcFormBox,
		NcFormBoxSwitch,
		NcFormGroup,
		NcRadioGroup,
		NcRadioGroupButton,
		NcSettingsSection,
		NcTextField,
		Dashboard,
	},

	data() {
		return {
			state: loadState<AdminConfig>('externalportal', 'admin-config'),
			saving: false,
			saved: false,
			lastChanged: null as string | null,
			saveTimeout: undefined as ReturnType<typeof setTimeout> | undefined,
			savedTimeout: undefined as ReturnType<typeof setTimeout> | undefined,
			defaultCustomColor: '#0082c9',
			iconUrl: imagePath('externalportal', 'externalportal-dark.svg'),
		}
	},

	computed: {
		previewTitle(): string {
			return this.state.widgetTitle || t('externalportal', 'External portal')
		},

		previewIconLabel(): string {
			return t('externalportal', '{title} icon', { title: this.previewTitle })
		},
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
				extraWide: this.state.extraWide ? '1' : '',
				showFiles: this.state.showFiles ? '1' : '',
				iconSize: this.state.iconSize,
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

<style scoped lang="scss">
.externalportal-settings {
	display: flex;
	flex-wrap: wrap;
	gap: calc(var(--default-grid-baseline) * 8);

	&__form {
		display: flex;
		flex-direction: column;
		gap: calc(var(--default-grid-baseline) * 6);
		flex: 1 1 300px;
		max-width: 600px;
	}

	// NcRadioGroupButton marks the selected button only with a background
	// color, which forced colors mode removes. Use the system highlight instead.
	&__size :deep(div:has(> input[type='radio']:checked)) {
		@media (forced-colors: active) {
			forced-color-adjust: none;
			background-color: Highlight;
			border-color: Highlight;
			color: HighlightText;
		}
	}

	&__color {
		// Lines the button up with the radio labels above it.
		padding-inline-start: calc(var(--default-grid-baseline) * 2);
		margin-block-start: calc(var(--default-grid-baseline) * -4);
	}

	&__swatch {
		display: block;
		width: 20px;
		height: 20px;
		border-radius: 50%;
		box-shadow: inset 0 0 0 1px var(--color-border-dark);
	}

	&__preview {
		flex: 0 0 auto;

		h3 {
			margin-block: 0 calc(var(--default-grid-baseline) * 2);
			font-size: var(--default-font-size);
			font-weight: bold;
			color: var(--color-text-maxcontrast);
		}
	}
}

// Stands in for the dashboard background, so the panel's blur reads the same.
.preview-backdrop {
	padding: calc(var(--default-grid-baseline) * 4);
	border-radius: var(--border-radius-container-large);
	background-color: var(--color-background-plain, var(--color-primary-element));
	background-image: var(--image-background, none);
	background-size: cover;
	background-position: center;
}

// Copied from the dashboard app's .panel styles (Nextcloud 33-35).
.panel {
	width: 320px;
	max-width: 100%;
	box-sizing: border-box;
	background-color: var(--color-main-background-blur);
	backdrop-filter: var(--filter-background-blur);
	border-radius: var(--border-radius-container-large);
	transition: width var(--animation-quick) ease-out;

	&.externalportal--extra-wide {
		width: 400px;
	}

	.panel--header {
		display: flex;
		padding: 16px;

		h2 {
			flex-grow: 1;
			margin: 0;
			padding: 16px 8px;
			height: 56px;
			box-sizing: border-box;
			font-size: 20px;
			line-height: 24px;
			font-weight: bold;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;

			img {
				float: inline-start;
				width: 32px;
				height: 32px;
				margin-top: -6px;
				margin-inline: 6px 16px;
				filter: var(--background-invert-if-dark);
			}
		}
	}

	.panel--content {
		margin: 0 16px 16px;
		height: 424px;
	}
}
</style>
