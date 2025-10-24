// SPDX-FileCopyrightText: Tuomas Nurmi <dev@opinsys.fi>
// SPDX-License-Identifier: AGPL-3.0-or-later
const path = require('path')
const webpackConfig = require('@nextcloud/webpack-vue-config')


webpackConfig.entry = {
  dashboard: path.join(__dirname, 'src', 'dashboard.js'),
  admin: path.join(__dirname, 'src', 'adminSettings.js'),
}

// Shorten output file names:
webpackConfig.output = {
  ...webpackConfig.output,
  filename: 'externalportal-[name].js', // e.g. externalportal-dashboard.js
  chunkFilename: 'import_[id].[contenthash:8].js', // used for dynamic imports
  clean: true, // cleans old files
}

webpackConfig.optimization = {
  ...webpackConfig.optimization,
  chunkIds: 'deterministic',
  moduleIds: 'deterministic',
}

module.exports = webpackConfig
