# Changelog

All notable changes to the ADDLAR theme. Keep the top version in sync with
`Version:` in `style.css` — the updater reads it from there.

## [1.0.1] — 2026-07-23

### Fixed
- **Journey, Packages and Numbers sections imported empty.** Elementor keeps
  control and section ids in a single flat namespace, so a `start_controls_section()`
  id identical to an `add_control()` id stopped the control registering — and
  with it the repeater's `default` rows. Affected `journey` (`rows`),
  `package-grid` (`items`), `stat-band` (`stats`) and, unreported but equally
  broken, the film beats in `applications` (`beats`). Section ids are now
  suffixed `_section`.
  `insights`, `product-grid` and `why-list` had the same clash but were masked
  because the seeder passes their rows explicitly; fixed for consistency.

## [1.0.0] — 2026-07-23

First release. Converts the approved static one-page design into a bespoke
Elementor theme.

### Added
- 12 section widgets, auto-registered from `widgets/class-*.php`:
  Hero, Trust Strip, About, Journey, Why List, Product Grid, Package Grid,
  Applications, Stat Band, Product Finder, Insights, Closing CTA.
- Customizer-driven header and footer with three nav walkers (header dropdown
  with description sub-labels, flattened mobile panel, footer columns).
- Page seeder building the homepage as Elementor **Containers**, sideloading
  the bundled images into the Media Library, available from
  **Tools → ADDLAR setup** and as `wp addlar seed`.
- Product finder catalogue editable as `Sub-category: CODE, CODE` lines,
  parsed to JSON for the front-end (7 categories / 25 sub-categories / 74 codes).
- GitHub over-the-air updates via vendored Plugin Update Checker 5.6.
- Tests: `tests/test-finder.php` (13 assertions) and `tests/compare.js`
  (66-element CSS parity check against the static mockup).

### Notes
- Design tokens are namespaced `--adl-*` and all selectors scoped under `.adl`
  so nothing collides with Elementor's container custom properties.
- `assets/css/tokens.css` and `assets/css/widgets.css` are generated from the
  mockup; hand-written CSS belongs in `assets/css/theme.css`.
- No webfonts: the design uses a system font stack.

### Changed after first review — nothing is a prerequisite
- Videos ship in `assets/video/` and are imported by the seeder, so the hero is
  live on first run with no manual upload.
- Media is matched by filename in the Media Library before importing, so
  re-seeding (or a cleared cache / migrated site) re-links to existing uploads
  instead of duplicating a 7MB video.
- Header, footer columns and bottom bar fall back to the designed navigation
  until menus are assigned.
- Customizer contact/social values default to the approved design; templates
  read them through `addlar_mod()` so the registered defaults actually apply.
- Over-the-air updates are optional and silent when unconfigured, and the repo
  is set via constant / option / filter rather than by editing theme code.
