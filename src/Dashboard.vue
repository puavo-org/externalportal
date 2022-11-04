
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
<div id="external-portal-widget">
  <span v-if="loading" class="icon icon-loading">ok loading</span>
  <span v-else-if="content">
  <div v-for="item in content">
    <a :href="item.url">
    <img width=100% height=100% preserveAspectRatio="xMinYMin meet" :src="item.icon" />
    {{ item.name }}
    </a>
  </div>
  <span/>
  <NcEmptyContent v-else
    :icon="emptyContentIcon">
    empty
  </NcEmptyContent>
</div>
</template>

<script>

import axios from '@nextcloud/axios'
import { DashboardWidget, DashboardWidgetItem } from '@nextcloud/vue-dashboard'
import { generateOcsUrl } from '@nextcloud/router';

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
      return t('externalportal', 'No external')
    },
    emptyContentIcon() {
      return 'icon-close'
    },
  },
  beforeMount() {
    this.getContent()
  },
  mounted() {
    document.getElementById("external-portal-widget").parentNode.parentNode.style.width="400px" 
  },
  methods: {
    getContent() {
      let url = generateOcsUrl('apps/external/api/v1', 2);
      if(url.endsWith("/")) //behaviour seems to have changed between 24 and 25
        url=url.slice(0, -1);
      axios.get(url).then((response) => {
        this.content = response.data.ocs.data;
        console.debug('"' + JSON.stringify(response.data) + '"')
      }).catch((error) => {
        console.debug(error)
      }).then(() => {
        this.loading = false
      })
    },
  },
}
</script>

<style scoped lang="scss">
#external-portal-widget {
overflow: scroll;
height: 100%;
padding: 0 10px 0 10px;
}

</style>
