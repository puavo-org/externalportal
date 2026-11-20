# External Portal changelog

## [3.0.0] - 2026-11-20
### Changed
- Redesigned the dashboard widget with the Nextcloud design system: sites are shown as round app icons like the app menu, with loading, error and empty states.
- Redesigned the admin settings page with Nextcloud components and a live preview of the widget.
- New icon styles: theme color, monochrome, custom color and original icons.
- New icon size setting (small, medium or large). It replaces the "Limit icon size" option.
- Raster icons (PNG, JPG, GIF) are drawn no larger than their natural size, so they no longer blur. SVG icons fill the icon circle.
- The widget supports dark and high contrast themes.
- New App Store screenshots.
- Update Finnish and German translations.
### Fixed
- Original icons stay visible on dark themes.
- The selected icon size is shown in forced colors mode.
- Update follow-redirects to 1.16.3 and qs to 6.17.1 for security advisories.

## [2.3.0] - 2026-11-16
### Changed
- Compatibility with Nextcloud 35.
- Supported Nextcloud versions: 33-35. Nextcloud 32 is no longer supported.
- Require PHP 8.2+, matching the minimum of Nextcloud 33.
- Update runtime dependencies: @nextcloud/dialogs to 8.0.0, @nextcloud/vue to 9.14.1, @nextcloud/router to 3.2.0 and vue to 3.5.45.
- Update development dependencies, including rector/rector and js-yaml.
### Fixed
- The Files shortcut label on the dashboard widget is now translated.
- The description of the icon preview in the admin settings is now translated.
- The German translation catalog could not be compiled; it is repaired and all catalogs are regenerated.
- Accessibility: the loading spinner has a label, the load error is announced to screen readers, link icons no longer repeat the link text, and the colour input has a fallback name.
- Update bundled dependencies for security advisories, including axios 1.20.0 and dompurify 3.4.17.

## [2.2.0] - 2026-09-02
### Changed
- Compatibility with Nextcloud 34.
- Supported Nextcloud versions: 32-34. Nextcloud 31 is no longer supported.
- Update runtime dependencies: @nextcloud/dialogs to 7.5.0 and @nextcloud/vue to 9.10.0.
- Error toasts are now shown at the bottom of the page, following the reworked toast notifications in @nextcloud/dialogs 7.5.0.
- Update development dependencies, including @vue/tsconfig, rector/rector and the composer development tooling.
### Fixed
- The dashboard widget now shows an error message when loading the external sites fails, instead of appearing empty.
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
