# External Portal changelog

## [2.2.0] - 2026-09-02
### Changed
- Compatibility with Nextcloud 34.
- Supported Nextcloud versions: 32-34. Nextcloud 31 is no longer supported.
- Update runtime dependencies: @nextcloud/dialogs to 7.5.0 and @nextcloud/vue to 9.10.0.
- Error toasts are now shown at the bottom of the page, following the reworked toast notifications in @nextcloud/dialogs 7.5.0.
- Update development dependencies, including @vue/tsconfig, rector/rector and the composer development tooling.
### Fixed
- Update dompurify to 3.4.14, fixing an XSS issue (GHSA-55q2-fjhq-7xh7) in the bundled HTML sanitizer.

## [2.1.0] - 2026-03-03
### Changed
- Compatibility with Nextcloud 33.
### Fixed
- Fix translations loading issue

## [2.0.0] - 2026-02-25
### Changed
- Updated to latest Nextcloud app template
- Require Node.js 20+
- Supported Nextcloud versions: 31-32
- Added translations for finnish and german
### Fixed
- Widget link logic (#15, ported from #8)

## [1.3.2] - 2025-10-24
### Fixed
- Shorten filenames in the js directory to fix issue 9.
- Update dependencies.
- New certificate for signing releases.

## [1.3.1] - 2024-07-31
### Added
- Bump supported Nextcloud versions.
### Fixed
- Update dependencies to fix some security issues.

## [1.3.0] - 2024-05-06
### Added
- Bump supported Nextcloud versions.
### Fixed
- Update dependencies to fix some security issues.
- Fix some lint warnings
- Try to ensure "Files" gets proper translation on NC >= 29

## [1.2.0] - 2023-06-03
### Added
- Add preview to Admin Settings page.
- Implement dynamically colored icons.
### Changed
- Update dependencies, require Nextcloud 25.
- Enable more lint workflows and format code style accordingly.

## [1.1.0] - 2023-04-03
### Added
- Option to limit icon maximum size to avoid overly stretched pixmap icons.
- Bump supported Nextcloud versions.
### Fixed
- Update dependencies to fix some security issues.

## [1.0.2] - 2022-11-21
### Fixed
- Packaging/package signing to avoid issues with integrity check showing missing files.
- More Makefile parameters improvements for better packaging workflow.

## [1.0.1] - 2022-11-18
### Added
- Option to include a link to the Files app.

### Fixed
- Better positioning with long page titles, better positioning avoiding clipping of elements.
- Makefile parameters improved for better packaging workflow.

## [1.0.0] - 2022-11-15
### Added
- Initial version of the app. Functionalities include a Nextcloud dashboard widget, which uses site data for the current users as provided by the External sites app. Widget title and small UI specifics are configurable on the app's settings page.
