# Changelog

All notable changes to the ADDLAR theme. Keep the top version in sync with
`Version:` in `style.css` — the updater reads it from there.

## [1.4.0] — 2026-08-19

Third round of feedback: two confirmed rendering bugs from `v1.3.0` (an
oversized mark obscuring photos, a literal "Heading" placeholder), and a
firm "this doesn't look like the homepage" — the product page's design
patterns (plain checklist, grey chips, bare tables) were considerably
plainer than the homepage's own sections.

### Fixed
- **Oversized ADDLAR mark.** `.spec-hero-image img` / `.image-grid-item img`
  in `theme.css` used a bare `img` selector, which also matched the
  `<img class="cmark">` mark and out-specificity'd `.adl .cmark`'s
  `width: 34px` — the mark rendered at its native ~400px size, obscuring
  most of the photo underneath it. Fixed with `img:not(.cmark)`, the
  pattern this codebase already uses correctly elsewhere (`widgets.css`:
  `.about-img img:not(.cmark)`, `.pcard .imgwrap img:not(.cmark)`).
- **Literal "Heading" text.** `Addlar_Widget_ImageGrid`'s `title` control
  defaults to the literal string `"Heading"`; the tile-row seed call never
  passed a `title`, so Elementor fell back to that default and printed it.
  Fixed by passing `'title' => '', 'eyebrow' => '', 'lede' => ''`
  explicitly — general lesson recorded in code comments: every
  heading-capable widget seeded via `addlar_build_tree()` needs its
  heading keys set explicitly, even to empty strings, never omitted.
- **Dead whitespace.** Every fragment got the homepage's full 104px
  section padding regardless of content size, so a two-line description
  or one chip row read as mostly empty. `Addlar_Widget_ProductFragment`
  gained a `compact` mode (`.section-tight`, 36px) used for every small
  text/table fragment; full padding stays for genuinely substantial
  sections.

### Changed — product page rebuilt on the homepage's own components
- **Key Performance Benefits** is now `.about-feats`/`.afeat` icon
  capability cards — the exact markup/CSS the homepage's About section
  uses — instead of a bordered checklist. `addlar_product_benefit_bullets()`
  now returns an icon alongside each bullet, mapped 1:1 from which real
  field it came from (applications → gear, spec string → shield, approval
  count → globe, performance-level count → layers, viscosity → viscosity).
- **OEM & Industry Approvals** now reuses `Addlar_Widget_TrustStrip`
  directly (no new widget) via a new `addlar_product_approval_strip_items()`
  parser, instead of plain grey chips.
- **New "Product at a Glance" band** reuses `Addlar_Widget_StatBand`
  directly (a `columns` control added, default unchanged so the homepage's
  own usage is untouched) showing real counts — applications, performance
  levels, approvals, documented properties — never a fabricated number,
  and the whole section is skipped if fewer than 2 counts are available.
- Section order reflowed to match: hero → benefits cards → mood banner →
  description/applications → at-a-glance stats → performance table →
  photo tiles → approvals strip (if any) → remaining data → related
  products → closing CTA.

## [1.3.0] — 2026-08-19

Second round of feedback on the product page redesign: still not visual
enough, every page image needs the ADDLAR mark, the category archive
condition doesn't appear in Elementor's Theme Builder picker, and a
guaranteed non-Elementor fallback was requested for that.

### Added
- `Addlar_Widget_ImageBanner`: full-bleed background-photo band with a dark
  scrim and centered text — the section type the reference competitor pages
  use repeatedly and the redesign was missing (everything was plain white
  sections with one contained photo). Interleaved into every product page
  between the benefits box and the data tables.
- `Addlar_Widget_ImageGrid` gained a `tile` style (bordered card, title
  overlaid on the photo) alongside the original caption-under-image style,
  used to break up the product page's table sections with a 2-tile row
  (the product's own photo + a link into its category archive).
- 6 new free-license stock photos (one more per category, sourced from
  Unsplash), so a product page's hero/banner/tile sections don't all show
  the exact same photo. Checked each candidate by hand and rejected ones
  with a competitor's logo/branding visible in frame (an Audi badge, a
  Mobil 1 oil bottle) before picking the final six.
- Every image-bearing widget touched this pass (spec header, image banner,
  image grid, related products) can now render the ADDLAR mark in the
  image's bottom corner, matching the treatment already used on the
  homepage's Product Grid.
- `addlar_category_template_mode` setting (Tools → ADDLAR setup): forces
  the category archive to use the coded template
  (`taxonomy-addlar_product_category.php`) instead of the Elementor Theme
  Builder template, by clearing the Theme Builder template's condition so
  Elementor never intercepts the URL. Guaranteed to work regardless of
  whether Elementor recognises the taxonomy.

### Fixed (best-effort)
- `addlar_product_category` taxonomy registration now sets `show_ui`,
  `show_in_nav_menus` and `show_admin_column` explicitly (previously
  implied only through `public => true`) — a plausible but unconfirmed
  cause of the taxonomy not appearing in Elementor Theme Builder's
  condition picker. Can't be verified without a live Elementor install,
  which is exactly why the settings toggle above exists as a guaranteed
  fallback independent of whether this fix actually resolves it.

## [1.2.0] — 2026-08-19

### Changed
- **Product pages redesigned and re-architected.** Client feedback against
  real competitor references (Afton Chemical, Lubimax) called for a visual,
  photo-forward layout instead of a plain data page, and for each product
  to be individually Elementor-editable rather than governed by one shared
  Theme Builder template. Every one of the 22 products is now seeded as its
  own standalone page (`addlar_seed_products()`), openable directly in
  Elementor, with a two-column spec hero (real product photo + title/spec/
  CTA) and a "Key Performance Benefits" checklist box above the existing
  data tables.
- Real product photography: 7 of the 22 products use their actual LinkedIn
  campaign graphic (downloaded from the client's Drive), imported as the
  post's featured image; the other 15 use their category's stock photo as a
  documented fallback rather than a blank space.
- The old shared "ADDLAR Product — Single" Theme Builder template is
  trashed on re-seed (`addlar_remove_stale_product_template()`) — Theme
  Builder's condition would otherwise silently keep overriding every
  product's new standalone content.
- About Us, Contact Us and Ask the Expert are fully designed pages, not
  placeholders — About Us uses the client's own copy (Drive: `Content/About
  Us Page.docx`); Contact Us and Ask the Expert have a real, working
  contact form (`inc/contact-form.php`, plain HTML + `wp_mail()`, no form
  plugin) using the exact field set proposed in the client's own
  requirements survey.

### Added
- 5 new widgets: `ProductSpecHeader` (redesigned), `ProductBenefits`,
  `RichText`, `ImageGrid`, `ContactInfo`, `ContactForm` — all
  `.adl`-scoped, reading post meta directly rather than via Dynamic Tags.
- `addlar_product_benefit_bullets()`: derives the benefits-box bullets from
  data already transcribed for each product (a real application, the real
  spec string, a real count of approvals/performance levels) — never an
  invented performance claim.

### Known gap
- 2 of the 22 products (7750, 9300) have a real marketing graphic only as a
  multi-slide PDF carousel, not a single image — not imported this pass;
  they use their category's stock photo like the other 13 without one.
- The client's About Us copy states the HQ is in Dubai; the theme's own
  Phase 1 Customizer default (still live everywhere else on the site) says
  Sharjah. Flagged, not silently resolved — see the page's own content vs.
  `addlar_mod('addlar_address')`.

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
