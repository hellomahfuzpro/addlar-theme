# Changelog

All notable changes to the ADDLAR theme. Keep the top version in sync with
`Version:` in `style.css` — the updater reads it from there.

## [1.25.0] — 2026-09-11

### Changed
- **Unified Site-Wide Typography to Montserrat Only (`functions.php`, `assets/css/tokens.css`, `style.css`, `assets/css/widgets.css`)**:
  - Removed Google Font `Quattrocento` completely from assets enqueue and CSS `@import`.
  - Switched all heading selectors (`h1`, `h2`, `h3`, `h4`, `h5`, `h6`, `.title`, `.heading`, `.jh-title`, `.showcase-headline`, `.chem-title`, `.contact-title`, `.cf-header h3`, `.stitch-head`) to `Montserrat` with bold/extra-bold weights (`700`/`800`) and refined tracking (`letter-spacing: -0.02em`).
  - Enforced `Montserrat` on `html`, `body`, and all text elements via `style.css` with `!important` to override any Elementor kit typography rules.
  - Eliminated extra font network request, improving site performance and load times.

## [1.24.1] — 2026-09-10

### Fixed
- **About Page "Comprehensive package. Complexity, simplified" (`assets/css/widgets.css`)**:
  - Restored full dark obsidian styling (`.apps`, `.appwrap`, `.applist`, `.appitem`, `.aic`, `.beats`, `.beat`).
  - Fixed unbounded SVG sizing inside `.aic` (was expanding to 1136px width, pushing section height to 5195px). Constrained to 42px flex box with 20px SVG.
  - Added balanced 2-column grid layout for standalone icon lists on desktop.
  - Restored high-contrast dark theme background and red glowing radial accents.
- **Product Single Page Middle Section (`assets/css/widgets.css`)**:
  - Fixed deformed "Product at a glance / by the numbers" section (`https://addlar-rc.com/product/engine-oil-additive/7706/`).
  - Added 2-column grid layout for `.appwrap` containing the hexagon clip stage (`.appstage`, `.appvid`) and staged stats (`.numgrid-staged`).
  - Constrained `.appvid` hexagon clip to 430px max-width, 1/1.06 aspect ratio, and centred the droplet logo mark (`.appdrop`).
  - Reduced middle section height from 1637px down to a clean ~650px.

### Changed
- **Showcase Tabs & Dock Styling (`assets/css/widgets.css`)**:
  - `.showcase-dock`: aligned to the right (`justify-content: flex-end`) with 20px padding.
  - `.showcase-tab-bar`: removed border-radius, border, and box-shadow; set padding to 0px.
  - `.showcase-tab-btn`: removed border-radius, set padding to 16px 16px, font-size 12px, font-weight 700.
- **Trust Specification Ribbon Spacing (`assets/css/widgets.css`, `widgets/class-trust-strip.php`)**:
  - Updated `.trust-spec-ribbon .wrap`: `margin-bottom: 0px !important; padding-bottom: 0px; padding-top: 10px;`.
  - Removed hardcoded inline `margin-bottom: 22px;` from heading wrap in `class-trust-strip.php`.

## [1.24.0] — 2026-09-10

### Added
- **Form Submissions Dashboard (`inc/admin-submissions.php`, `functions.php`)**:
  - Custom database table `wp_addlar_submissions` auto-installed on theme load.
  - "Inquiries" admin menu with dynamic unread badge counter.
  - Submissions list table with status/preset filters, keyword search, pagination.
  - Detail view modal with full inquiry data and one-click email reply.
  - CSV export of all submissions.
  - "Notification Settings" sub-page: configurable recipient emails, sender name/email, subject prefix, and notification toggle.
- **Contact Form DB Storage (`inc/contact-form.php`)**:
  - Every Contact Us and Ask the Expert submission is now persisted to the database before sending email notifications.
  - Email notifications respect the admin-configured recipients, sender, and subject prefix.

### Changed
- **Specification Ribbon → White Section (`assets/css/widgets.css`, `inc/demo-import.php`)**:
  - Moved "Meets the following specifications" directly below Journey (Hero → Journey → Specs → Tabbed Showcase).
  - Restyled from dark charcoal (`#0B0C10`) to crisp white (`#FFFFFF`) with light borders, dark specification pills, and brand red accents.
- **Closing CTA → Dark Obsidian (`assets/css/widgets.css`)**:
  - Transformed from red gradient overlay to sleek dark obsidian industrial section (`#0D0F12` with desaturated photo, dark gradient, frosted glass spec chips).
  - Eliminates red-on-red collision with the Stitch contact banner footer.
- **Contact Us Page Streamlined (`inc/demo-import.php`, `assets/js/theme.js`)**:
  - Removed duplicate `addlar_contact_form` widget from Contact Us page body.
  - Hero "Send a message" button now links to `#contact` (footer banner).
  - Added `#form` → `#contact` smooth scroll fallback for backward compatibility.

### Fixed
- **Giant 306px SVG Icons (`assets/css/theme.css`)**:
  - Added explicit `.adl .phex` sizing (48px container, 24px SVG) preventing SVGs from stretching to fill their unconstrained parent.
  - Fixes blown-up icons on About Us, Contact Us, and Ask the Expert pages.
  - Added red-fill hover state when parent `.pkg` card is hovered.

## [1.23.0] — 2026-09-10

### Added
- **Stitch Contact & Footer Section (`footer.php`, `assets/css/widgets.css`)**:
  - Implemented the full Stitch design for the site footer, featuring a vibrant red gradient contact banner (`background: linear-gradient(135deg, #E52D27 0%, #B31217 50%, #7E0A0E 100%)`) with direct technical phone, e-mail inquiries, Sharjah Al Hamriyah Free Zone address, and a translucent glass message form with interactive reCAPTCHA box and black action button.
  - Integrated deep dark industrial footer (`#0B0D0F`) with hexagon gear watermark, white ADDLAR logo, ISO 9001:2015 and UAE Blending Facility badges, Quick Links, Contact Details, round social media buttons, and the ADDLAR Catalogue download guide card.
  - Fully wired to WordPress `admin_post_addlar_contact_submit` with anti-spam honeypot and nonce protection.

### Changed
- **Section Starting Texts / Eyebrows Unified (`widgets/class-trust-strip.php`, `widgets/class-applications.php`, `widgets/class-product-grid.php`, `assets/css/widgets.css`)**:
  - Unified section starter texts (*Meets the following specifications*, *Technical Architecture • Seven Families*, *Product Range — Six Additive Families*) with theme standard `.eyebrow` styling (`color: var(--adl-red); font-size: 13px; font-weight: 800; letter-spacing: .18em; text-transform: uppercase;`).
  - Standardized the horizontal 28px × 2px red indicator bar `::before` while removing pill capsules, rounded borders, and dots.
- **Homepage Section Order Restructured (`inc/demo-import.php`)**:
  - Shifted *"Eight reasons formulators depend on us"* (`addlar_why_list`) down to sit immediately above *"The chemistry inside every package"* (`addlar_applications`).
  - Updated homepage section sequence: Hero &rarr; Journey &rarr; Tabbed Product Showcase &rarr; Specification Ribbon &rarr; Eight Reasons &rarr; The Chemistry &rarr; Numbers &rarr; Product Finder &rarr; Insights.
- **Dynamic Font Size Containment for Large Numerals (`widgets/class-why-list.php`, `assets/css/widgets.css`)**:
  - Wrapped `.imgnum` in `.imgnum-wrap` with container queries (`container-type: inline-size`) and responsive clamp sizing (`min(350px, 66cqw)`), guaranteeing numerals never clip or break out of their container on any screen width or viewport ratio.

## [1.22.0] — 2026-09-10

### Added
- **Interactive 3D Powertrain Chemistry Stage (`widgets/class-applications.php`)**:
  - Upgraded "The chemistry inside every package" section with a central 3D wireframe car video (`wheels-spinning-car.mp4`) bundled directly into `assets/video/`.
  - Solid pure black background (`#000000`) with seamless radial vignette and screen blend mode eliminating video container borders and box shadows.
  - Sub-pixel calibrated hotspot pins on exact powertrain components (Differential, Wheel Bearings, Driveshaft, Valvetrain & Sump, Engine Block, Pistons & Crank, Transmission).
  - All 7 text cards styled permanently as sharp red boxes (`border: 2px solid var(--adl-red); border-radius: 0;`) with red header/footer bars and high-contrast dark slate body.
  - Dynamically computed SVG connector lines from card edges directly to dot centers with strict spatial ordering to guarantee zero line crossings.
  - Interactive laser glow highlight on hover/focus without distracting auto-cycling flashes.
  - Technical storytelling beats strip at the bottom.
- **Specification Ribbon (`widgets/class-trust-strip.php`)**:
  - Shifted down directly below the Tabbed Product Showcase, replacing the former package grid section.
  - Added heading support with default text: *"Meets the following specifications"*.
  - Removed the *"10,000 MT Annual Capacity"* item to focus strictly on lubricant performance standards (API, ACEA, ILSAC, JASO, UAE Manufactured).
- **Global Theme Button & Component Styling (`assets/css/tokens.css`, `assets/css/theme.css`, `assets/css/widgets.css`, `style.css`)**:
  - Replaced legacy `999px` pill border-radius with `border-radius: 4px !important;` across all global theme buttons (`.btn`, `.btn-red`, `.btn-primary`, `.btn-white`, `.btn-secondary`, `.btn-ghost`, `.btn-outline`, `.elementor-button`, `.wp-block-button__link`), navbar CTA, mobile nav CTA, contact form, product finder pills/options, chips, and pagination.
  - Aligned elevation shadows, font sizing (14px Montserrat, weight 700, letter-spacing .02em), padding (14px 28px), and hover translateY(-1px) lift to strictly match the Product Range — Industrial Fluids showcase buttons site-wide.
- **Numbers Section Dynamic Centering (`widgets/class-stat-band.php`, `assets/css/widgets.css`)**:
  - Converted `.numgrid` to dynamic flexbox centering, ensuring 1, 2, 3, 4, or 5 statistics are always symmetrically centered on the page.
- **Insights Section 3-Column Grid (`widgets/class-insights.php`, `assets/css/widgets.css`)**:
  - Converted LinkedIn Insights from a 1-column vertical list to a modern 3-column horizontal card grid with top imagery, categorized tags, titles, and hover elevation.
- **Homepage Section Order Restructuring (`inc/demo-import.php`)**:
  - Reordered default seeder: Hero &rarr; Journey &rarr; Eight Reasons (`why-list`) &rarr; Tabbed Product Showcase (`product-grid`) &rarr; Specification Ribbon (`trust-strip`) &rarr; The Chemistry (`applications`) &rarr; Numbers (`stat-band`) &rarr; Product Finder &rarr; Insights.
  - Removed About, Package Grid, and Closing CTA from the default homepage array while retaining their widget classes in the theme.

## [1.21.1] — 2026-09-10

### Fixed
- **Tabbed Product Showcase Scoping & Resilience**:
  - Added `<div class="adl">` container wrapping around the tabbed slider showcase section in `widgets/class-product-grid.php`.
  - Added dual-scoping to all showcase slider selectors in `assets/css/widgets.css` (`.adl .prod-slider-showcase, .prod-slider-showcase` and `.adl .showcase-*, .showcase-*`) ensuring proper styling is applied in all Elementor container configurations.
  - Added explicit font-family fallbacks (`Quattrocento, Georgia, serif` and `Montserrat, sans-serif`) to ensure typography stays consistent even if inherited variables are undefined.
  - Added `padding: 0;` override to `.prod-slider-showcase` to prevent theme default section padding interference.

## [1.21.0] — 2026-09-10

### Added
- **Tabbed Product Showcase Slider (Stitch Design)**:
  - Integrated the full-width tabbed slider showcase designed in Stitch (**Addlar - Tabbed Product Showcase: One Partner, Every Lubrication Challenge**, screen `e684c9114a5a4c42acc33a8d60a323a7`) into `widgets/class-product-grid.php`.
  - Added `layout_style` control: choose between **Full-Width Tabbed Slider (Stitch Design)** (default) and **Classic 6-Card Grid**.
  - Six rich category slide presets with high-res machinery photography, technical specs, dual CTAs, and performance metrics:
    1. **Automotive Engine Oils** (`API CK-4 / SP`, `ACEA E11/E9/C5`, `ILSAC GF-6`, `JASO MA2`, `0W-16 to 15W-40`).
    2. **Driveline & Transmission Additives** (`API GL-5`, `TO-4`, `Off-Road`, `Heavy Axle`, `FZG Stage 12+`).
    3. **Marine Cylinder & System Lubricants** (`BN 20 to 140`, `Slow-speed 2-stroke`, `Bunker fuel`).
    4. **Industrial Hydraulic & Circulating Fluids** (`DIN 51524`, `ISO 11158`, `Parker Denison HF-0`).
    5. **Metalworking Fluids & Neat Oils** (`Chlorine-free EP`, `Biostable emulsions`, `Up to +40% tool life`).
    6. **Specialty Components & VI Improvers** (`OCP Polymer SSI 22/35`, `PIB`, `400 TBN Sulfonates`).
  - Full-width dark carbon background (`#111315`) with cinematic gradient overlays and subtle technical grid pattern.
  - Floating bottom glass dock with 6 category tabs and custom SVG icons, highlighted by a high-octane red active pill.
  - Interactive controls: smooth background cross-fade, next/prev circular arrows, and 3 clickable mini machinery thumbnails.
  - Responsive layout for desktop, tablet, and mobile with horizontally scrollable dock.

## [1.20.0] — 2026-09-10

### Changed
- **Why List Number Section Fix**:
  - `.adl .wrow`: updated grid layout to `grid-template-columns: minmax(0, 0.7fr) minmax(0, 1fr)`.
  - `.adl .imgnum`: enlarged numerals to 350px in `Montserrat` with `font-weight: 900 !important;`, `line-height: .82 !important;`, `letter-spacing: -.030em !important;`, masked photo fill via `-webkit-background-clip: text` and `-webkit-text-fill-color: transparent`.
- **Fitted Journey Row & Removed Scrollbar**:
  - Removed bottom draggable scrollbar track and drag gestures completely.
  - Sized track so milestones fit cleanly in a single horizontal row (`width: 100%; min-width: 0;` with `grid-auto-flow: column;` and dynamic column sizing) without getting cut off at the edge and without wrapping to a second row.
  - Added Arrow Position control with options for Top Right, Bottom Center, Middle Left/Right, or Hidden.
  - Year box styling: added `border-radius: 8px` ("more radius to year box") and clean 16px vertical spacing to hexagons.
- **Elementor Style Controls Added**:
  - Hexagons & Line: Hexagon color, plate background, size (slider 60–120px), connecting line color.
  - Year Badges: Border radius (slider 0–30px), spacing to hexagon (slider 6–35px), background color, text color.
  - Milestone Cards: Card border radius, height (slider 140–260px), background color, border color.
  - Navigation Arrows: Background, icon color, hover background, hover icon color.

## [1.19.0] — 2026-09-10

### Changed
- **Journey Spacing & Year Styling**:
  - Added clear 14px vertical space between year boxes and hexagons.
  - Added subtle 4px border radius (`border-radius: 4px`) to year boxes.
- **Journey Navigation & Draggable Customization**:
  - Removed draggable option and drag gesture listeners.
  - Removed the hint text *"Drag or swipe to explore our journey"*.
  - Repositioned navigation arrows to the vertical middle on the left and right sides of the timeline track (`top: 50%; transform: translateY(-50%)`).
  - Added responsive Elementor control for `items_per_row` (Desktop, Tablet, Mobile).
- **Fixed Why List (Photo inside Numeral) Section**:
  - Restored complete `.why2`, `.wrow`, and `.imgnum` styles with `-webkit-background-clip: text`, `font-size: clamp(74px, 8.4vw, 124px)`, and alternating RTL/LTR alignments so numerals are properly photo-filled and not squished.

## [1.18.0] — 2026-09-10

### Changed
- **Journey Hexagon Colors to Red**:
  - Double-layered clip-path hexagons styled with High-Octane Red (`var(--adl-red, #D32F2F)`) border and matching red icons (`stroke: var(--adl-red, #D32F2F)`).
  - Inner core plate set to soft red tint (`#FFF5F5`) with crisp white separator gap.
- **Removed Box Shadows & Border Radius**:
  - Removed all `box-shadow` and `border-radius` from milestone cards, kicker badges, and year tags, adhering strictly to the brand architectural precision rule ("buttons are the ONLY rounded elements").
  - Removed drop-shadow filter from journey hexagons.
- **Strictly Limited Scope**:
  - Ensured hero section, header navigation, and all other sections remain completely in their original default state.

## [1.17.0] — 2026-09-10

### Changed
- **Site-Wide Industrial Precision Color Palette**: Implemented the client's official color palette across the entire theme and site tokens:
  - Primary Accent: High-Octane Red (`#D32F2F`)
  - Core Base: Deep Charcoal / Off-Black (`#1A1D20`)
  - Supporting Neutral: Technical Slate (`#4A5568`)
  - Background Tint: Fluid Light Grey (`#F8F9FA`)
  - Primary Canvas: Clean White (`#FFFFFF`)
- **Site-Wide Typography (Quattrocento & Montserrat)**: Enqueued Google Fonts and updated typography enforcement:
  - Headings & Display: `Quattrocento`, serif (bold, elegant technical editorial)
  - Body & UI: `Montserrat`, sans-serif (clean geometric legibility)
- **Journey Widget 3-Band Angle Wave Row Update**: Strictly updated only the journey row inside the section:
  - Equal-height milestone cards (`175px`) with flex layout absorbing text length variations.
  - Direct 42px vertical stems connecting cards to hexagons without gaps.
  - 8 double-layered clip-path hexagons with centered icons positioned with a `116px` vertical wave delta.
  - Unbroken dashed polyline (`#CBD5E1`, `stroke-dasharray: 7 5`) directly connecting the mathematical centers of all 8 hexagons.
  - Dark Charcoal year pills (`#1A1D20`) with white text.
  - Milestone 07 updated to `2025` ("ADDLAR is launched") and cleanly unified with neutral palette (no highlighted milestone).
  - Section wrapper, heading, and container untouched.

## [1.16.0] — 2026-09-10

### Changed
- **Preserved Exact Legacy Design for Our Journey**: Adapted the signature brand design (double-layer clip-path hexagons with outline rings and inner tints, bracket rules with open ring terminals, uppercase tracking, bold colored year typography) seamlessly into horizontal layouts without generic cards or dots.
- **Three Core Layouts**:
  - `Horizontal: Alternating Wave`: Milestones alternate above and below the central track line with layered hexagons in the center axis.
  - `Horizontal: Linear`: Milestones consistently aligned with year meta above, layered hexagon on central axis, and heading/description below.
  - `Vertical: Interlocking Chain`: 100% original legacy interlocking vertical hexagon chain with alternating left/right layout.
- **Container Width Options**: Boxed (1240px centered) and Full Width (100% edge-to-edge).
- **Draggable / Grid Mode**: Toggle between a draggable horizontal track (with smooth mouse-drag and chevron nav controls) and a multi-row centered grid (dividing into 3, 4, 5, or 6 items per row).
- **Milestone 07 Year Fixed**: Maintained date `2025` for "ADDLAR is launched".

## [1.15.0] — 2026-09-06

### Added
- **Design 2 Alternating Wave**: Added modern roadmap card styling alternating above and below the central track line.
- **Design 2 Linear Roadmap**: Added modern roadmap card styling hanging uniformly below top track.
- **Boxed vs Full Width Container**: Added container width control allowing the Journey section to be boxed (1240px centered) or full width (100% edge-to-edge).
- **Draggable / Scroll Track Switcher**: Toggle between a single continuous horizontal draggable track (with grab cursor and scroll buttons) and a multi-row grid system.
- **Items Per Row Control**: Configurable items per row (`3`, `4`, `5`, or `6` items per row, default `4`) when draggable is turned off, dividing milestones into clean, centered horizontal rows with zero horizontal scrollbar.

## [1.14.0] — 2026-09-06

### Changed
- **Horizontal Journey Timeline**: Implemented refined horizontal journey timeline layout based on the clean v1.11.0 site foundation. Features mathematically aligned 3-row columns (`jh-col`) with continuous central gradient axis, alternating top and bottom milestone cards, and vertical mobile rail fallback (< 960px).
- **Milestone 07 Date Correction**: Corrected year for Milestone 07 (ADDLAR is launched) from `2023–2025` to `2025`.
- **All Other Sections Retained**: Retained all original v1.11.0 homepage sections, layouts, styles, and configurations without modification.

### Added
- **Theme Rollback & Version Switcher ("Rollout")**: Built-in dashboard feature under **Tools → ADDLAR setup** and **Appearance → Theme Rollback** (and WP-CLI `wp addlar rollback <version>`), allowing administrators to switch or downgrade to any available theme version at any time with 1 click.

## [1.11.0] — 2026-08-19

### Changed
- **The blog page is now a standalone, fully Elementor-editable page**,
  like every other page on the site — no longer a coded template.

  It had to be coded before because of a real WordPress constraint:
  assigning a page as the "Posts page" (Settings → Reading) hands that URL
  to `home.php` and **ignores the assigned page's own content entirely**,
  so nothing arranged in Elementor there would ever render. The fix is to
  stop using that setting. `/blog/` is now an ordinary page, the seeder
  clears `page_for_posts`, and the articles list is a widget.
- **New `Addlar_Widget_PostGrid`** — the articles list as an editable
  block, with controls for posts per page, category filter, pagination,
  heading and the empty-state message. Pagination reads both `paged` and
  `page` query vars, since a static page and the `/blog/page/2/` rewrite
  supply different ones.
- The page is assembled from existing widgets: dark hero with breadcrumb,
  the homepage's own Insights widget for the LinkedIn band, the new Post
  Grid, and the red closing band — so every section is editable and
  reorderable on the canvas.
- `home.php` is removed. Its job is now the page itself, and keeping it
  would mean two competing blog designs if the Posts-page setting were
  ever switched back on.
- `index.php` rewritten as the archive and fallback template — category,
  tag, author, date and search now get the same dark hero and card grid
  instead of the bare unstyled list they had before, with a context-aware
  heading (a category archive names its category).
- `single.php` no longer resolves its back-link through `page_for_posts`;
  it uses `addlar_nav_blog_url()`, which is correct either way.

## [1.10.0] — 2026-08-19

### Added
- **The blog page now carries the LinkedIn section above the article
  grid**, the same featured posts shown on the homepage. The two are
  clearly separated rather than left for the reader to work out: each band
  has its own eyebrow label and heading — *From LinkedIn / Discussion on
  our showcase page* and *Articles / Published on this site* — the
  articles band sits on a soft ground, and LinkedIn cards say "Read on
  LinkedIn" and open in a new tab while articles say "Read article".
- The empty state now reads as intentional: when nothing is published
  yet, the articles band says so and points at the LinkedIn discussion
  above, instead of showing a bare heading over nothing.

### Changed
- **LinkedIn posts now have one definition.** They had been written out
  twice already — as the Insights widget's control defaults and again in
  the seeder — and the blog page needed them a third time. All three now
  read `addlar_linkedin_posts()` (`inc/linkedin-posts.php`), so updating a
  post is one edit rather than three that can silently disagree. The set
  is overridable via the `addlar_linkedin_posts` filter.
- The blog page resolves LinkedIn images read-only — it prefers the copy
  the seeder imported into the Media Library, so a replaced image is
  honoured, and falls back to the bundled file. It deliberately never
  calls the seeder's import path, which would sideload media during a
  visitor's page request.

## [1.9.0] — 2026-08-19

All 22 Product Data Sheets re-read from Drive and transcribed properly.

### Fixed — real data errors found by re-reading the source
- **7009's kinematic viscosity was 160 cSt. That is its Base Number.** The
  real value is 65 cSt, and its density (1025 kg/m3) was missing entirely.
- **Six products had the wrong "Appearance" value**, all recorded as
  "Brown Viscous Liquid" when their PDS says otherwise: KC311 (Yellow
  Light Viscous), KC321 (Brown Clear), KC420 / KC562 / KC563 (Clear
  Yellow) and Z 2612 (Bright & Clear).

### Added
- **Every typical-properties row now carries its real test method.** All
  200 rows across the 22 products previously rendered "—" in the Method
  column, because an earlier pass refused to guess ASTM codes rather than
  risk printing a wrong standard next to a client's lab value. They are
  now read off the PDFs: ASTM D445, D1298, D92, D2896, D3228, D5185 /
  D4951, D874, D130, D2783, and Visual/Internal where the sheet says so.
- **Product descriptions are now the client's own PDS copy**, replacing
  the one-line summaries written here. These are real, approved marketing
  text — "maximises engine durability by providing exceptional wear
  protection…" — and feed the product hero's subtitle.
- KC420's applications carry the detail its own PDS gives: "Deep Hole
  Drilling (e.g. gun drilling)", "Forming and Stamping (especially on
  tough materials like stainless steel)", "Cold Heading or Cold Forming".
- Two regression tests, since both problems above shipped undetected:
  every property row must have a non-empty method, and every description
  must be a full paragraph rather than a one-line summary. Test count is
  now 775 assertions.

### Note
9100 and 9300 still show "—" for viscosity and density. Those cells are
genuinely blank in the source PDS — not a transcription gap.

## [1.8.0] — 2026-08-19

### Fixed
- **Site header sat behind the WordPress admin bar when logged in.** WP
  adds `html { margin-top: 32px }` and pins its toolbar to the viewport
  top; our header is `position: fixed; top: 0`, which ignores that margin.
  Logged out (and in incognito) there is no toolbar, which is why it only
  affected admins. The header is now offset by the toolbar's height, at
  the same breakpoints WordPress itself uses — 32px above 782px, 46px
  below it, and back to 0 under 600px where the toolbar stops being fixed
  and scrolls away. The mobile nav panel is offset to match.

### Added
- **Blog templates.** `home.php` (listing) and `single.php` (post), both
  built on the site's existing components: the dark hero with breadcrumb,
  the homepage Insights section's `.licard` grid for the post list, and
  the red closing band. `.post-body` gets real element styling —
  headings, lists, quotes, code, tables, images — because WordPress
  content can contain any block, unlike the fixed section widgets used
  everywhere else. Styled pagination included.
- **Category descriptions.** Each product family now has a one-line
  description, used as the category archive hero's subtitle (terms
  previously had none, leaving that hero on generic filler). They
  describe what each family is and which sub-types it covers — both facts
  already in the Finder catalogue and PDS set; none asserts a performance
  characteristic. Applied on re-seed to existing terms too.

### Changed
- **Menu and footer point at real destinations.** The primary nav gains
  Insights; Applications and Finder are absolute links to the homepage
  and Products page rather than bare `#anchors`, which did nothing when
  clicked from a product page. Footer columns are now Company / Product
  Range (the six real category archives) / Resources (Ask the Expert,
  Finder, Applications, Insights), with columns 2 and 3 renamed to match.
  The legal column is deliberately still `#` — those pages need writing
  and approval before being linked anywhere.

## [1.7.0] — 2026-08-19

### Changed
- **The red Closing CTA band is back on product pages**, replacing the
  black bar introduced in 1.6.0 — it's the stronger close, and its spec
  chips now carry the product's own specification rather than generic
  copy. (The black `CtaBar` widget stays, used for the LinkedIn follow
  bar on Contact Us and Ask the Expert, which is its role on the
  homepage.)
- **The Applications section now has the homepage's hex-clipped image on
  the left** (`.appwrap`/`.appstage`/`.appvid` reused as-is). Without it
  the list read as a plain bullet column rather than the homepage's
  signature layout.
- **Products with no Applications section get the hexagon on their
  numbers band instead**, so the treatment appears on every product page.
  Only some of the 22 products have documented applications (raw
  components like Z 2612 don't), and `Addlar_Widget_StatBand` gained an
  optional hexagon stage for exactly this — off by default, so the
  homepage's own stat band is untouched.

### Changed — Products page and category archives redesigned
- `/products/` now opens with the dark hero (breadcrumb, spec chips,
  buttons), then the six family cards, then the homepage's three-step
  Product Finder — this is the page people land on to choose a product,
  so the Finder belongs on it — and closes on the red CTA band.
- Category archives use the same dark hero, with the title and subtitle
  taken from the term itself (`use_archive_term` on the hero widget), the
  product grid, and the red CTA band.
- **Breadcrumbs were missing from both.** They now sit inside the dark
  hero, which carries the fixed-header offset — a standalone breadcrumb
  strip at the top of a page renders underneath the fixed header and is
  invisible, which is the same bug fixed for product pages in 1.6.0.
- The coded category template (`taxonomy-addlar_product_category.php`,
  used when the Theme Builder route is switched off) got the same dark
  hero and breadcrumb, so the fallback isn't visibly a different design.

## [1.6.0] — 2026-08-19

### Changed — URL structure
- Products now use the requested three-level scheme. The single-product
  base is **singular** (`/product/`) while the taxonomy owns the plural
  (`/products/`), which removes the base collision that caused the
  original archive 404 rather than working around it:

  | Page | URL |
  |---|---|
  | Products overview | `/products/` |
  | Category archive | `/products/{category}/` |
  | Single product | `/product/{category}/{product}/` |

  The category is folded into a product's path via a
  `%addlar_product_category%` placeholder substituted per post
  (`addlar_product_permalink()`). The rewrite tag is registered
  explicitly — if it were missing, WordPress would leave the literal
  placeholder in the rule and every product URL would 404.

### Fixed
- **Breadcrumbs were invisible on product pages.** The site header is
  `position: fixed` at 78px and the homepage hero clears it with its own
  `margin-top: 78px`; the breadcrumb, as the page's first element, had no
  such offset and rendered underneath the header. It now lives inside the
  hero (which carries the offset), so it can't be hidden — and the
  standalone breadcrumb widget got the same offset for archive pages.

### Changed — product page composition
- **Applications** now use the homepage Applications section's dark
  icon-list treatment (`Addlar_Widget_IconList`, reusing `.applist`/
  `.appitem`) instead of a row of chips.
- **"Product at a glance"** keeps the homepage's big-number band and now
  carries a background image, matching the homepage's own stat band.
- **Removed** on request: the "Engineered for real-world performance"
  photo banner, the two-image tile block under Viscosity grades, and the
  Related Products grid.
- **Added** `Addlar_Widget_CtaBar` — the homepage's black LinkedIn-bar
  treatment (`.li-follow`) reused as a compact closing CTA, replacing the
  removed tile block. It also replaces the red Closing CTA on product
  pages so the page doesn't end on two stacked calls to action; say the
  word if you'd rather have both.

### Changed — About Us / Contact Us / Ask the Expert
- All three rebuilt on the same components as the homepage and product
  pages: dark hero with breadcrumb and spec chips, hex-icon spec cards,
  the dark icon list, the big-number stat band, and a black CTA bar —
  replacing the plainer page-intro/rich-text layouts.

## [1.5.1] — 2026-08-19

### Fixed
- **The dashboard never offered theme updates, so every release had to be
  uploaded by hand.** The updater was written as opt-in: `addlar_github_repo()`
  defaulted to an empty string and `addlar_updates_enabled()` therefore
  returned false, so the update checker never booted — silently, by design,
  waiting for an `ADDLAR_GITHUB_REPO` constant in `wp-config.php` that was
  never added on the live site. It now defaults to the theme's real
  repository (`ADDLAR_GITHUB_REPO_DEFAULT`), still overridable per install
  by the same constant/option/filter, and settable to an empty string to
  turn updates off deliberately.
  Verified separately that nothing else in the chain was at fault: the
  update-checker library is committed and does ship inside the release zip
  (128 files), the repo is public, and the latest release is a published
  (non-draft) `v1.5.x` tag with `addlar.zip` attached.
- The update slug is now derived from the theme's actual directory name
  rather than the hardcoded string `addlar`, so an install whose folder was
  named differently can still receive updates.

### Added
- **Tools → ADDLAR setup → Theme updates**: shows installed version, update
  source, whether updates are enabled, and the theme folder name, plus a
  "Check for updates now" button that bypasses the update checker's cache.
  The previous failure was invisible from the admin; this makes it legible.

> **One last manual upload.** The install currently running still has the old
> opt-in updater, so it cannot discover this fix on its own. Upload v1.5.1
> once by hand — after that, releases appear under Appearance → Themes.

## [1.5.0] — 2026-08-19

Product pages rebuilt again, this time changing where content *lives*, not
just how it looks — plus the category-archive 404 root-caused properly and
breadcrumbs added.

### Changed — content moved out of custom fields, into Elementor widgets
- **`inc/products-metabox.php` and the whole post-meta content pipeline are
  gone.** Product content (headline, spec chips, benefit cards, every data
  table) is now written directly into each page's own Elementor widget
  settings at seed time, so the client edits it on the Elementor canvas.
  Previously it was: metabox textarea → `save_post` → pre-rendered HTML in
  post meta → a widget echoing that meta. Four moving parts became one.
- Only two structural meta values remain (`_addlar_code`,
  `_addlar_subcategory`) — the Finder and related-products queries look
  products up by them. Re-seeding also deletes the now-stale content meta
  an upgraded install would otherwise still be carrying.
- New standalone widgets, all with normal Elementor inputs and no meta
  dependency: `ProductHero`, `SpecCards`, `SpecTable`, `ChipList`,
  `Breadcrumb`. The three meta-reading widgets they replace
  (`ProductSpecHeader`, `ProductBenefits`, `ProductFragment`) are removed.

### Changed — visual design
- **Product hero is now a full-bleed dark section with a red diagonal
  wedge**, oversized headline and spec chips — the same visual weight as
  the homepage hero, replacing the white text-on-white header that made
  product pages read as a different site.
- Benefit cards use the homepage Package Grid's hex-icon cards
  (`.pkg`/`.phex`); approvals reuse the homepage certification strip;
  "at a glance" reuses the homepage stat band. Chips are squared and
  red-accented rather than small grey pills.

### Fixed
- **Category archives 404'd.** Root cause: the taxonomy's rewrite slug was
  `products/category`, nested underneath *both* the hand-curated
  `/products/` Page and the CPT's own `products` rewrite base — a
  three-way collision no amount of permalink flushing could resolve. The
  taxonomy now uses a distinct top-level base, `/product-category/…/`,
  which cannot collide with either. The taxonomy is also registered
  before the post type, and a version-keyed one-shot `flush_rewrite_rules()`
  runs on update so the new URLs work without a manual permalinks re-save.
- Breadcrumbs added to product pages and category archives
  (`Addlar_Widget_Breadcrumb`), archive-aware so a taxonomy archive shows
  the term name rather than the first product's title.

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
