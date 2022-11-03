
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
guiding source for basic dashboard widget functionality.
-->

<template>
<div id="external-widget">
<span v-if="loading" class="icon icon-loading">ok loading</span>
<span v-else-if="content" v-html="content">
<span/>
<NcEmptyContent v-else
:icon="emptyContentIcon">
<template #desc>
empty
</template>
</NcEmptyContent>
</div>
</template>

<script>
import { DashboardWidget, DashboardWidgetItem } from '@nextcloud/vue-dashboard'
export default {
  name: 'Dashboard',
  components: {
    DashboardWidget
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
      content: 'empty',
    }
  },
  computed: {
    emptyContentMessage() {
      return t('external', 'No external')
    },
    emptyContentIcon() {
      return 'icon-close'
    },
  },
  mounted() {
  },
}
</script>
