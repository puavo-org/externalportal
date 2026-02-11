// SPDX-FileCopyrightText: Opinsys Oy <dev@opinsys.fi>
// SPDX-License-Identifier: AGPL-3.0-or-later

import { createApp } from 'vue'

import AdminSettings from './AdminSettings.vue'

const app = createApp(AdminSettings)
app.mount('#externalportal_prefs')
