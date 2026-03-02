<template>
	<div id="external-portal-widget">
		<div v-if="loading" class="icon icon-loading" />
		<div v-else-if="content.length > 0" class="external-sites">
			<div v-for="item in content"
				:key="item.id"
				:class="itemClasses">
				<a :href="itemHref(item)"
					:target="item.redirect ? '_blank' : ''"
					:rel="item.redirect ? 'noopener' : ''">
					<div v-if="themingColor !== undefined"
						class="linkitem masked-icon"
						:style="`mask-image: url(${item.icon}); background-color: ${themingColor}`" />
					<img v-else
						class="linkitem"
						preserveAspectRatio="xMinYMin meet"
						:src="item.icon">
					<span class="linkname">
						{{ item.name }}
					</span>
				</a>
			</div>
		</div>
		<div v-else>
			<span>{{ t('externalportal', 'No external sites.') }}</span>
		</div>
	</div>
</template>

<script lang="ts">

import axios from '@nextcloud/axios'
import { generateOcsUrl, generateUrl } from '@nextcloud/router'
import { t } from '@nextcloud/l10n'

interface ExternalSite {
	id?: number
	name: string
	url: string
	icon: string
	redirect?: number
}

export default {
	name: 'Dashboard',
	data() {
		return {
			loading: true,
			content: [] as ExternalSite[],
			extraWide: '' as string | boolean,
			maxSize: '' as string | boolean,
			showFiles: '' as string | boolean,
			iconColorMode: 'DEFAULT',
			customIconColor: '',
		}
	},
	computed: {
		themingColor() {
			if (this.iconColorMode === 'CUSTOM') {
				return this.customIconColor
			} else if (!OCA.Theming || this.iconColorMode === 'PRIMARY') {
				return 'var(--color-main-text)'
			} else if (this.iconColorMode === 'THEMING') {
				return OCA.Theming.color
			} else {
				return undefined
			}
		},
		itemClasses() {
			return {
				externalsite: true,
				smaller: this.content.length > 4 && this.content.length < 7,
				smallest: this.content.length > 6,
				maxsized: this.maxSize,
			}
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
			if (!panel) return
			if (this.content.length > 6 && this.extraWide) {
				panel.classList.add('externalportal--extra-wide')
			} else {
				panel.classList.remove('externalportal--extra-wide')
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
				this.maxSize = response.data.maxSize
				this.showFiles = response.data.showFiles
				this.iconColorMode = response.data.iconColorMode
				this.customIconColor = response.data.customIconColor
			} catch (e) {
				console.error(e)
			}

			if (this.showFiles) {
				const filesUrl = generateUrl('/apps/files')
				const filesIconUrl = generateUrl('../apps/files/img/app.svg')
				const filesLabel = t('files', 'Files')
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
			try {
				const response = await axios.get(url)
				this.content = this.content.concat(response.data.ocs.data)
			} catch (error) {
				console.error(error)
			}
			this.loading = false
			this.updateExtraWide()
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
#external-portal-widget {
	overflow-x: hidden;
	overflow-y: auto;
	height: 100%;
	text-align: center;

	.external-sites {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-evenly;
		overflow: hidden;
	}

	div.externalsite {
		width: 48%;
		display: inline-block;
		vertical-align: top;
		padding-inline: 0.1rem;
		box-sizing: border-box;

		div.masked-icon, img {
			filter: drop-shadow(5px 5px 5px #888);
		}

		img {
			padding: 0.5rem;
		}

		div.masked-icon {
			background-color: var(--color-primary);
			mask-size: 90%;
			mask-position: center;
			mask-repeat: no-repeat;
			aspect-ratio: 1;
			box-sizing: border-box;
			height: auto;
			padding: 4px;
		}

		.linkname {
			text-align: center;
		}
	}

	div.icon-loading {
		margin: 1rem;
	}

	div.smaller {
		width: 39%;
	}

	div.smallest {
		width: 30%;
	}

	div.maxsized {
		max-width: 80px;
		margin: 0.5rem;

		.linkitem {
			max-width: 64px;
			max-height: 64px;
		}
	}

	.linkitem {
		width: 95%;
		height: auto;
		margin-top: 0.5rem;
	}

	.linkitem:hover {
		transition: transform .2s;
		transform: scale(1.1);
		box-shadow: 0px 0px 0.1em 0.1em #f5f5f582, inset 0 0 2em 1em #f5f5f582;
	}
}
</style>
