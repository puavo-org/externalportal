# External Portal

A Nextcloud Dashboard widget providing a view of the current user's external sites. This app uses directly the sites provided by [External sites](https://apps.nextcloud.com/apps/external) API, which is thus required for meaningful usage of this app.

This app is not affiliated with the External sites app. Implementing the corresponding functionality in the External sites app would have also been a possibility - however, due to the very limited interoperability required, a separate app was chosen as a lighter solution.

## Requirements

- Nextcloud 31 or 32
- PHP 8.1+
- Node.js 20+
- [External sites](https://apps.nextcloud.com/apps/external) app installed and configured

## Building the app

Install dependencies and build:

```bash
npm install
npm run build
```

For development with file watching:

```bash
npm run watch
```

## Linting

```bash
npm run lint
npm run stylelint
composer cs:check
composer psalm
```

## Releasing a new version

1. Update the version in `appinfo/info.xml` and `package.json`
2. Update `CHANGELOG.md`
3. Commit and tag the release:
   ```bash
   git add appinfo/info.xml package.json CHANGELOG.md
   git commit -m "vx.x.x"
   git tag vx.x.x
   git push && git push --tags
   ```
4. Build and sign the appstore package:
   ```bash
   make sign
   ```
   This uses `docker exec` to run `occ integrity:sign-app` inside the
   `master_nextcloud_1` container. Signing certificates must be placed in
   `~/.nextcloud/certificates/` (`externalportal.key` and `externalportal.crt`).

   To build without signing, run `make` instead.
5. Upload `build/externalportal.tar.gz` to the [Nextcloud App Store](https://apps.nextcloud.com/developer/apps/releases/new)
