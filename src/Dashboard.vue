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
	<div id="external-portal-widget">
		<div v-if="loading" class="icon icon-loading" />
		<span v-else-if="number > 0">
			<div v-for="item in content"
				:key="item.id"
				:class="{ smaller: content.length>4 && content.length < 7, smallest: content.length>6, maxsized: maxSize}">
				<a v-bind="{ target: item.sameWindow ? '' : '_blank' }" :href="item.url">
					<img class="linkitem"
						preserveAspectRatio="xMinYMin meet"
						:src="item.icon">
					<span class="linkname">
						{{ item.name }}
					</span>
				</a>
			</div>
		</span>
		<div v-else>
			<span>No external sites.<br>Home sweet home.</span>
		</div>
	</div>
</template>

<script>

import axios from '@nextcloud/axios'
import { generateOcsUrl, generateUrl } from '@nextcloud/router'
import { translate as t } from '@nextcloud/l10n'

export default {
	name: 'Dashboard',
	components: {
	},
	props: {
		title: {
			type: String,
			required: true,
		},
	},
	data() {
		return {
			loading: true,
			number: 0,
			content: [],
			extraWide: false,
			maxSize: false,
			showFiles: false,
		}
	},
	computed: {
	},
	beforeMount() {
		this.getConfig()
		this.getContent()
	},
	mounted() {
	},
	methods: {
		getConfig() {
			const url = generateUrl('/apps/externalportal/config')
			axios.get(url).then((response) => {
				this.extraWide = response.data.extraWide
				this.maxSize = response.data.maxSize
				this.showFiles = response.data.showFiles
				console.debug('"' + JSON.stringify(response.data) + '"')
			}).catch((error) => {
				console.debug(error)
			}).then(() => {
				if (this.showFiles) {
					const filesUrl = generateUrl('/apps/files')
					const filesIconUrl = generateUrl('../apps/files/img/app.svg')
					const filesLabel = t('files', 'Files')
					this.content = [{ icon: filesIconUrl, url: filesUrl, name: filesLabel, sameWindow: true }].concat(this.content)
					this.number = this.content.length
				}
				if (this.number > 6 && this.extraWide) {
					document.getElementById('external-portal-widget').parentNode.parentNode.style.width = '400px'
				}
			})
		},

		getContent() {
			let url = generateOcsUrl('apps/external/api/v1', 2)
			if (url.endsWith('/')) { // behaviour seems to have changed between 24 and 25
				url = url.slice(0, -1)
			}
			axios.get(url).then((response) => {
				this.content = this.content.concat(response.data.ocs.data)
				this.number = this.content.length
				console.debug('"' + JSON.stringify(response.data) + '"')
				console.debug('"' + JSON.stringify(this.content) + '"')
			}).catch((error) => {
				console.debug(error)
			}).then(() => {
				this.loading = false
				if (this.number > 6 && this.extraWide) {
					document.getElementById('external-portal-widget').parentNode.parentNode.style.width = '400px'
				}
			})
		},
	},
}
</script>

<style scoped lang="scss">
#external-portal-widget {
overflow-y: scroll;
height: 100%;
text-align: center;

div {
	width: 48%;
	display: inline-block;
	vertical-align: top;
	padding-inline: 0.1rem;
	margin-bottom: -1rem;

	img {
		padding: 0.5rem;
    -webkit-filter: drop-shadow(5px 5px 5px #888);
    filter: drop-shadow(5px 5px 5px #888);
	}
	.linkname {
	position: relative;
	top: -0.75rem;
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
	max-width: 64px;
	margin: 0.5rem;
}
.linkitem {
	width: 95%;
	height: 95%;
	margin-top: 0.5rem;
}
.linkitem:hover {
	transition: transform .2s;
	transform: scale(1.1);
	box-shadow: 0px 0px 0.1em 0.1em #f5f5f582, inset 0 0 2em 1em #f5f5f582;
}
}

</style>
