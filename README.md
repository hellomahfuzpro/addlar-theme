# ADDLAR — WordPress theme

Bespoke Elementor-powered theme for **ADDLAR by Rchemie International**, built
from the approved static design in `../index.html`.

Every homepage section is a custom Elementor widget; the header and footer are
theme templates driven by the Customizer. Optional over-the-air updates from
GitHub Releases.

The theme is self-contained: images, videos and the full navigation ship with
it, so a fresh install is complete after a single click. Nothing has to be
configured before the site looks right.

---

## Requirements

| | |
|---|---|
| WordPress | 6.0+ |
| PHP | 7.4+ (linted against 7.4) |
| Elementor | required — Pro on the target site |

## Install

1. Upload the theme zip via **Appearance → Themes → Add New → Upload Theme**.
2. Activate it. If Elementor is inactive you'll get an admin notice.
3. Go to **Tools → ADDLAR setup** and press **Seed homepage**.

That is the whole install. The seeder imports the images and both videos,
builds every section, and sets the front page. Navigation, footer links and
contact details all fall back to the approved design until you assign your own
menus and Customizer values — see `CONTENT-CHECKLIST.md`.

> Over-the-air updates are **optional and off by default** — see below. The
> first install is always manual, since a version predating the updater has
> nothing to run.

## Architecture

```
addlar/
├── style.css              theme header + Version: (the updater reads this)
├── functions.php          supports, enqueues, module includes
├── header.php / footer.php  Customizer-driven chrome
├── front-page.php         the_content() — Elementor renders the sections
├── assets/
│   ├── css/tokens.css     GENERATED — tokens, base, header/footer
│   ├── css/widgets.css    GENERATED — one block per section
│   ├── css/theme.css      hand-written WordPress-only additions
│   ├── js/theme.js        nav, reveals, counters, product finder
│   ├── images/            bundled defaults, sideloaded on seed
│   └── video/             hero + applications clips (see CONTENT-CHECKLIST)
├── inc/
│   ├── customizer.php     header CTA, contact, social, footer + defaults
│   ├── nav-walker.php     header dropdown / mobile / footer walkers
│   ├── nav-defaults.php   designed navigation used until menus are assigned
│   ├── icons.php          inline SVG icon set
│   ├── finder-data.php    catalogue parser (unit-tested)
│   ├── elementor.php      panel category + glob widget autoloader
│   ├── updater.php        GitHub release updates
│   └── demo-import.php    page seeder (Containers) + Tools page + WP-CLI
├── widgets/               one file per section, auto-registered
├── lib/plugin-update-checker/   vendored (MIT)
└── tests/                 PHP unit test + CSS parity harness
```

### CSS is generated — don't hand-edit two of these files

`assets/css/tokens.css` and `assets/css/widgets.css` are produced from the
static mockup by a port script that:

1. namespaces every custom property `--x` → `--adl-x`, and
2. scopes every selector under `.adl`.

Both matter. Elementor's flexbox containers define their own `--display`,
`--gap`, `--width` … custom properties; an unprefixed design token gets shadowed
inside a container and silently resolves to the wrong value. Scoping keeps the
mockup's generic class names (`.wrap`, `.btn`, `.section`) from colliding with
Elementor or plugin CSS.

**Put hand-written CSS in `assets/css/theme.css`** — it is never regenerated.

### Adding a section

Drop `widgets/class-my-thing.php` defining `Addlar_Widget_MyThing` extending
`Addlar_Base_Widget`. The autoloader derives the class name from the filename,
so the mapping must be exact (kebab-case file → StudlyCase class).

## Tests

```bash
# catalogue parser (no WordPress needed)
php tests/test-finder.php

# CSS parity: renders the mockup and the theme's CSS and diffs
# geometry + computed styles across 66 elements
node tests/compare.js 1440
```

`compare.js` builds the harness itself, expects `../index.html` (the static
mockup) one level up, and needs Chrome — override its path with the `CHROME`
env var. It exits non-zero on any drift.

## Releasing

```powershell
# 1. bump Version: in style.css, update CHANGELOG.md, commit
git add -A; git commit -m "vX.Y.Z: ..."; git push origin main
git tag -a vX.Y.Z -m "vX.Y.Z"; git push origin vX.Y.Z

# 2. build a forward-slash zip that extracts to an `addlar/` folder
$src="...\addlar-theme"; $tmp="$env:TEMP\rel"
Remove-Item -Recurse -Force $tmp -EA 0; New-Item -ItemType Directory -Force $tmp | Out-Null
Copy-Item -Recurse $src "$tmp\addlar"
& "$env:SystemRoot\System32\tar.exe" -a -c -f "$tmp\addlar.zip" -C $tmp addlar

# 3. publish
gh release create vX.Y.Z "$tmp\addlar.zip" --title "vX.Y.Z" --notes "..."
```

Use **bsdtar** (`System32\tar.exe`), never PowerShell's `Compress-Archive` — the
latter writes backslash path separators, which Linux hosts treat as part of the
filename, producing a theme of files literally named `addlar\style.css`.

Updates are optional. Point the theme at a repo with the `ADDLAR_GITHUB_REPO`
constant, the `addlar_github_repo` option (`wp addlar updates <url>`), or the
`addlar_github_repo` filter — no theme file needs editing. With none set, the
updater stays silent and everything else works normally.
