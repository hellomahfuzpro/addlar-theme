# Setup notes

**Nothing here is required.** Install the theme, press *Seed homepage*, and the
site is complete and correct — navigation, footer, contact details, images and
both videos all come from the theme. Everything below is about replacing
defaults with the client's own material.

---

## What the seeder does for you

Running **Tools → ADDLAR setup → Seed homepage** (or `wp addlar seed`):

- creates the homepage and sets it as the front page,
- lays out all 12 sections as Elementor containers,
- imports the bundled images **and both videos** into the Media Library,
- sets the site logo and footer mark.

It is safe to re-run. Media is matched by filename before importing, so a
re-run — or a cleared cache, or a site migration — re-links to the existing
uploads instead of importing a second 7MB copy of the hero video.

## Defaults you'll probably want to change

### Navigation
Until you assign menus, the header, footer columns and bottom bar render the
navigation from the approved design. To take control, create menus under
**Appearance → Menus** and assign them to *Primary*, *Footer column 1–3* and
*Footer bottom bar*. Assigning a menu replaces the default for that location
only.

If you build a Products dropdown, the sub-labels ("Heavy Duty · Passenger Car ·
Motorcycle") come from each child item's **Description** field — enable it via
**Screen Options** at the top of the Menus screen, as it's hidden by default.

### Contact details
**Customizer → ADDLAR — Contact & Social** ships with `info@rchemie.com`,
`rchemie.com`, the Sharjah address and the LinkedIn showcase URL. YouTube is
empty, so that icon stays hidden until you add a URL.

### Two figures to confirm
- **The market statistic.** The first tile in the Numbers band reads
  **$17B+ — "Lubricant additive market served"**. This is a placeholder using a
  commonly-cited industry market size; it was never supplied in the brief.
  Replace it with a figure the business can stand behind, or delete the tile.
- **"50+ years combined industry experience."** The brief asked for 50+, but the
  About copy and the LinkedIn posts all say 20 years / founded 2006. It's
  labelled *combined* to reconcile them — confirm that reading.

### Photography
The bundled photographs are free stock placeholders. They're in the Media
Library after seeding, so swapping one is just picking a different image in the
widget.

The three LinkedIn graphics are genuine ADDLAR artwork taken from the posts.
One caveat: the **KC562** artwork is only 387px wide — LinkedIn's ceiling for
document-post covers without authentication. Replace `li-2.jpg` if a
higher-resolution export exists.

### LinkedIn posts
The Insights section points at three specific posts and will age. Update it in
**ADDLAR Insights → Posts**: each row takes the artwork, a category label, the
headline, a summary and the direct post URL.

## Optional: over-the-air updates

Off by default and entirely optional — the theme runs normally without it and
shows no notices. To switch it on, point it at a GitHub repo by any one of:

```php
// wp-config.php
define( 'ADDLAR_GITHUB_REPO', 'https://github.com/acme/addlar' );
```
```bash
wp addlar updates https://github.com/acme/addlar   # or: wp addlar updates
```
```php
add_filter( 'addlar_github_repo', fn() => 'https://github.com/acme/addlar' );
```

Private repo? Supply a token the same three ways via `ADDLAR_GITHUB_TOKEN`, the
`addlar_github_token` option, or the matching filter.

Updates then appear under **Appearance → Themes**. The first install is always
manual — a version predating the updater has nothing to run.

## Optional: trimming the theme size

The theme is ~13MB because both videos ship inside it, so the site works
immediately with no upload step. After the first seed they live in the Media
Library, and the filename check means they will never be re-imported.

If update payload size matters more than self-containment, delete
`assets/video/` before packaging a release and set a remote source instead:

```php
add_filter( 'addlar_video_remote_sources', function () {
	return array(
		'hero-video' => 'https://example.com/hero-v3.mp4',
		'app-video'  => 'https://example.com/hero.mp4',
	);
} );
```

With neither a bundled file nor a remote source, each video falls back to its
poster image — the layout stays correct, it just doesn't move.
