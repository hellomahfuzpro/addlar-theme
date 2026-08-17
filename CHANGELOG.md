# Changelog

All notable changes to the ADDLAR theme. Keep the top version in sync with
`Version:` in `style.css` — the updater reads it from there.

## [1.1.1] — 2026-08-17

### Fixed
- **Product Theme Builder template rendered unstyled; category archive
  404'd.** Only `Addlar_Base_Widget` subclasses get this theme's `.adl` CSS
  scope (each one opens/closes it itself); the first cut of the product
  single template used Elementor's native Post Title / Text Editor / HTML /
  Posts widgets, which never get that wrapper — so the tables rendered with
  zero theme CSS and "related products" showed Elementor's default blog-post
  skin instead of the card grid. Rebuilt on four new custom widgets
  (`ProductSpecHeader`, `ProductFragment`, `RelatedProducts`, `PageIntro`)
  that read post meta directly via PHP instead of Dynamic Tags. Also seeds a
  category-archive Theme Builder template and flushes rewrite rules on seed
  — the real cause of a brand-new taxonomy's archive URL 404ing.
- Two `get_page_by_title()` calls (deprecated in WP 6.2) replaced with a
  small `WP_Query`-based lookup.

### Added
- Tools page: export either seeded Theme Builder template as an
  Elementor-importable `.json`, and a standalone "Flush permalinks" action.

## [1.1.0] — 2026-08-17

### Added
- `addlar_product` CPT + `addlar_product_category` taxonomy backing the 22
  real, PDS-documented ADDLAR products, with an Elementor Theme Builder
  single-product template and coded fallback templates
  (`single-addlar_product.php`, `taxonomy-addlar_product_category.php`).
- Tabular PDS data (performance levels, typical properties, applications,
  approvals, formulation examples) pre-rendered to HTML at save time and
  bound into Theme Builder — no ACF Pro dependency.
- Seeded `/products/` overview page and About Us / Contact Us / Ask the
  Expert / Blog stub pages, reachable from `Tools → ADDLAR setup → Seed
  products + pages` (also `wp addlar seed-products`).
- `tests/test-products.php` (177 assertions): catalogue-fix regression
  checks plus a transcription-error tripwire across all 22 products'
  table data.

### Fixed
- **Product Finder / catalogue data mismatch.** `KC420`, `Z 2612`, `7155`
  and `KC321` (confirmed Hydraulic, not just "Industrial") are folded into
  the Finder's default catalogue; two more documented products (`7375`,
  `7376`) were found missing from it during the PDS read pass and added too.
  The Finder's catalogue now derives from the CPT (`addlar_finder_catalogue_merged()`)
  instead of a hand-maintained parallel list, and a Finder pill with a real
  product page renders as a link to it.

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
