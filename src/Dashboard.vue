<template>
	<div class="external-portal">
		<div v-if="loading" class="external-portal__state">
			<NcLoadingIcon :size="32" :name="t('externalportal', 'Loading …')" />
		</div>
		<NcEmptyContent v-else-if="loadError"
			class="external-portal__state"
			role="alert"
			:name="t('externalportal', 'Could not load external sites.')">
			<template #icon>
				<NcIconSvgWrapper :path="mdiAlertCircleOutline" />
			</template>
			<template #action>
				<NcButton @click="reload">
					{{ t('externalportal', 'Try again') }}
				</NcButton>
			</template>
		</NcEmptyContent>
		<ul v-else-if="content.length > 0"
			class="external-portal__grid"
			:class="`external-portal__grid--${size}`"
			:aria-label="t('externalportal', 'External sites')">
			<li v-for="item in content" :key="item.id ?? item.url">
				<a class="site-tile"
					:href="itemHref(item)"
					:target="item.redirect ? '_blank' : undefined"
					:rel="item.redirect ? 'noopener noreferrer' : undefined"
					:title="item.name">
					<span class="site-tile__circle" :class="`site-tile__circle--${iconMode}`" :style="circleStyle">
						<img v-if="iconMode === 'original'"
							class="site-tile__image"
							alt=""
							:src="item.icon"
							:style="iconMaxStyle(item)">
						<span v-else
							class="site-tile__glyph"
							aria-hidden="true"
							:style="{ '--site-icon-url': `url('${item.icon}')`, ...iconMaxStyle(item) }" />
					</span>
					<span class="site-tile__label">{{ item.name }}</span>
				</a>
			</li>
		</ul>
		<NcEmptyContent v-else
			class="external-portal__state"
			:name="t('externalportal', 'No external sites.')">
			<template #icon>
				<NcIconSvgWrapper :path="mdiWeb" />
			</template>
		</NcEmptyContent>
	</div>
</template>

<script lang="ts">
import { mdiAlertCircleOutline, mdiWeb } from '@mdi/js'
import axios from '@nextcloud/axios'
import { t, translate } from '@nextcloud/l10n'
import { generateOcsUrl, generateUrl } from '@nextcloud/router'
import NcButton from '@nextcloud/vue/components/NcButton'
import NcEmptyContent from '@nextcloud/vue/components/NcEmptyContent'
import NcIconSvgWrapper from '@nextcloud/vue/components/NcIconSvgWrapper'
import NcLoadingIcon from '@nextcloud/vue/components/NcLoadingIcon'

interface ExternalSite {
	id?: number
	name: string
	url: string
	icon: string
	redirect?: number
}

/**
 * How the icons are drawn. Maps the stored `iconColorMode` setting:
 * - theme: tinted with the theme color, like the apps in the app menu
 * - text: monochrome in the text color
 * - custom: tinted with the admin's custom color
 * - original: the icon file as-is, for multicolor logos
 */
type IconMode = 'theme' | 'text' | 'custom' | 'original'

type IconSize = 'small' | 'medium' | 'large'

export default {
	name: 'ExternalPortalDashboard',
	components: {
		NcButton,
		NcEmptyContent,
		NcIconSvgWrapper,
		NcLoadingIcon,
	},

	setup() {
		return { mdiAlertCircleOutline, mdiWeb }
	},

	data() {
		return {
			loading: true,
			loadError: false,
			content: [] as ExternalSite[],
			extraWide: '' as string | boolean,
			showFiles: '' as string | boolean,
			iconSize: 'medium',
			iconColorMode: 'DEFAULT',
			customIconColor: '',
			// Natural widths of raster icons, by URL. Raster icons are drawn no
			// larger than this, as External sites only allows 16-32px rasters.
			rasterWidths: {} as Record<string, number>,
		}
	},

	computed: {
		size(): IconSize {
			return ['small', 'medium', 'large'].includes(this.iconSize)
				? this.iconSize as IconSize
				: 'medium'
		},

		iconMode(): IconMode {
			switch (this.iconColorMode) {
				case 'THEMING':
					return 'theme'
				case 'PRIMARY':
					return 'text'
				case 'CUSTOM':
					return this.customIconColor ? 'custom' : 'theme'
				default:
					return 'original'
			}
		},

		circleStyle(): Record<string, string> {
			return this.iconMode === 'custom'
				? { '--site-custom-color': this.customIconColor }
				: {}
		},
	},

	async mounted() {
		await this.getConfig()
		await this.getContent()
	},

	methods: {
		t,
		updateExtraWide() {
			const panel = (this.$el as HTMLElement).closest('.panel')
			if (!panel) {
				return
			}
			if (this.content.length > 6 && this.extraWide) {
				panel.classList.add('externalportal--extra-wide')
			} else {
				panel.classList.remove('externalportal--extra-wide')
			}
		},

		isRaster(url: string) {
			const path = url.split(/[?#]/)[0].toLowerCase()
			return !path.endsWith('.svg')
		},

		iconMaxStyle(item: ExternalSite): Record<string, string> {
			const width = this.rasterWidths[item.icon]
			return width ? { '--site-icon-max': `${width}px` } : {}
		},

		measureRasterIcons() {
			for (const item of this.content) {
				const url = item.icon
				if (!url || !this.isRaster(url) || url in this.rasterWidths) {
					continue
				}
				const image = new Image()
				image.onload = () => {
					if (image.naturalWidth > 0) {
						this.rasterWidths[url] = image.naturalWidth
					}
				}
				image.src = url
			}
		},

		itemHref(item: ExternalSite) {
			if (!item.redirect && item.id !== undefined) {
				return generateUrl('/apps/external/{id}/', { id: item.id })
			}
			return item.url
		},

		async getConfig() {
			const url = generateUrl('/apps/externalportal/config')
			try {
				const response = await axios.get(url)
				this.extraWide = response.data.extraWide
				this.showFiles = response.data.showFiles
				this.iconSize = response.data.iconSize
				this.iconColorMode = response.data.iconColorMode
				this.customIconColor = response.data.customIconColor
			} catch (e) {
				console.error(e)
			}

			if (this.showFiles) {
				const filesUrl = generateUrl('/apps/files')
				const filesIconUrl = generateUrl('../apps/files/img/app.svg')
				// Reuse the Files app's own translation of its name. `translate` is the
				// same function as `t`, but the string extractor only looks for `t`, so
				// this keeps a foreign app's string out of our own catalog.
				const filesLabel = translate('files', 'Files')
				this.content = ([{
					icon: filesIconUrl,
					url: filesUrl,
					name: filesLabel,
					redirect: 0,
				}] as ExternalSite[]).concat(this.content)
			}
		},

		async getContent() {
			let url = generateOcsUrl('apps/external/api/v1')
			if (url.endsWith('/')) {
				url = url.slice(0, -1)
			}
			this.loadError = false
			try {
				const response = await axios.get(url)
				this.content = this.content.concat(response.data.ocs.data)
			} catch (error) {
				this.loadError = true
				console.error(error)
			}
			this.loading = false
			this.updateExtraWide()
			this.measureRasterIcons()
		},

		async reload() {
			this.loading = true
			this.content = []
			this.updateExtraWide()
			await this.getConfig()
			await this.getContent()
		},
	},
}
</script>

<style scoped lang="scss">
// Tiles follow the app menu in the Nextcloud header (core AppMenuItem and
// AppIcon), so external sites look like the user's other apps.
// Separates original icons from the panel: a shadow on light themes, a light
// halo on dark ones, where black logos would otherwise vanish.
$image-shadow-light: drop-shadow(0 1px 2px rgb(0 0 0 / 35%));
$image-shadow-dark: drop-shadow(0 0 1px rgb(255 255 255 / 70%)) drop-shadow(0 0 3px rgb(255 255 255 / 30%));

$bevel:
	inset 0 -1px 0 0 color-mix(in srgb, var(--site-circle-color), 10% var(--site-glyph-color)),
	inset 0 -4px 6px -4px color-mix(in srgb, var(--site-circle-color), 16% var(--site-glyph-color));

.external-portal {
	height: 100%;
	overflow-x: hidden;
	overflow-y: auto;
	scrollbar-width: thin;
	scrollbar-color: var(--color-scrollbar) transparent;

	&__state {
		display: flex;
		align-items: center;
		justify-content: center;
		height: 100%;
	}

	&__grid {
		// Column widths are picked so a normal panel (288px of content) fits
		// four, three and three columns, and an extra wide one (368px) one more.
		--site-column-width: 64px;
		--site-circle-size: calc(var(--default-grid-baseline) * 12);
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(var(--site-column-width), 1fr));
		row-gap: calc(var(--default-grid-baseline) * 2);
		padding: var(--default-grid-baseline);
		margin: 0;
		list-style: none;

		// Small matches the app menu in the header.
		&--medium {
			--site-column-width: 88px;
			--site-circle-size: calc(var(--default-grid-baseline) * 16);
		}

		&--large {
			--site-column-width: 88px;
			--site-circle-size: calc(var(--default-grid-baseline) * 20);
			--site-label-size: var(--default-font-size);
		}
	}
}

.site-tile {
	--site-glyph-size: calc(var(--site-circle-size) * 7 / 12);
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: var(--default-grid-baseline);
	padding-block: var(--default-grid-baseline);
	border-radius: var(--border-radius-element);
	color: var(--color-main-text);
	text-decoration: none;
	min-width: 0;

	&:focus-visible {
		outline: none;
		box-shadow: inset 0 0 0 2px var(--color-primary-element);
	}

	&:hover,
	&:focus-visible {
		--site-circle-scale: 1.08;
	}

	&:active {
		--site-circle-scale: 0.96;
	}

	&__circle {
		// Theme color is the default look, matching the app menu.
		--site-circle-color: var(--color-primary-element-light);
		--site-glyph-color: var(--color-primary-element);
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: center;
		width: var(--site-circle-size);
		height: var(--site-circle-size);
		border-radius: 50%;
		transform: scale(var(--site-circle-scale, 1));
		transition: transform var(--animation-quick) ease-out;
		background-color: var(--site-circle-color);
		background-image: linear-gradient(
			to bottom,
			color-mix(in srgb, var(--site-circle-color), 15% var(--color-main-background)) 0%,
			var(--site-circle-color) 100%
		);
		--site-bevel: #{$bevel};
		--site-image-shadow: #{$image-shadow-light};
		box-shadow: var(--site-bevel);

		// Dark themes drop the bevel, like the app menu does.
		@media (prefers-color-scheme: dark) {
			--site-bevel: none;
			--site-image-shadow: #{$image-shadow-dark};
		}

		@media (prefers-reduced-motion: reduce) {
			transition: none;
		}

		&--text {
			--site-circle-color: var(--color-background-dark);
			--site-glyph-color: var(--color-main-text);
		}

		&--custom {
			--site-circle-color: color-mix(in srgb, var(--site-custom-color), 82% var(--color-main-background));
			--site-glyph-color: var(--site-custom-color);
		}

		// Multicolor logos keep their own colors. No circle: a light or dark
		// circle would hide white or black logos, so a soft shadow (a halo on
		// dark themes) separates them from the panel instead. Doubled class to beat the theme rules below.
		&#{&}--original {
			background: none;
			--site-bevel: none;
		}
	}

	&__glyph {
		// Raster icons stop at their natural size; scaled up they only blur.
		width: min(var(--site-glyph-size), var(--site-icon-max, var(--site-glyph-size)));
		height: min(var(--site-glyph-size), var(--site-icon-max, var(--site-glyph-size)));
		// Masked rather than shown: site icons ship a hardcoded fill, so the
		// color has to come from the background.
		background-color: var(--site-glyph-color);
		background-image: linear-gradient(
			to bottom,
			color-mix(in srgb, var(--site-glyph-color), 28% var(--site-circle-color)) 0%,
			var(--site-glyph-color) 100%
		);
		mask: var(--site-icon-url) center / contain no-repeat;

		@media (forced-colors: active) {
			background-color: CanvasText;
			background-image: none;
		}
	}

	&__image {
		--site-image-size: calc(var(--site-circle-size) * 5 / 6);
		width: min(var(--site-image-size), var(--site-icon-max, var(--site-image-size)));
		height: min(var(--site-image-size), var(--site-icon-max, var(--site-image-size)));
		object-fit: contain;
		filter: var(--site-image-shadow);
	}

	&__label {
		max-width: 100%;
		padding-inline: var(--default-grid-baseline);
		font-size: var(--site-label-size, 12px);
		line-height: 1.3;
		letter-spacing: -0.3px;
		text-align: center;
		hyphens: auto;
		overflow-wrap: break-word;
		// Long names wrap to two lines at most; the full name is in the title.
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
		line-clamp: 2;
		overflow: hidden;
	}
}

// An explicit theme choice must beat the media query above, which only sees the OS.
:global([data-themes*=dark] .site-tile__circle) {
	--site-bevel: none;
	--site-image-shadow: #{$image-shadow-dark};
}

:global([data-themes*=light] .site-tile__circle) {
	--site-bevel: #{$bevel};
	--site-image-shadow: #{$image-shadow-light};
}
</style>
