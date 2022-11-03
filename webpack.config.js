// SPDX-FileCopyrightText: Tuomas Nurmi <dev@opinsys.fi>
// SPDX-License-Identifier: AGPL-3.0-or-later
const path = require('path')
const webpackConfig = require('@nextcloud/webpack-vue-config')


webpackConfig.entry = {
  dashboard: path.join(__dirname, 'src', 'dashboard.js'),
  main: path.join(__dirname, 'src', 'main.js'),
}

module.exports = webpackConfig
