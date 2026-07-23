/**
 * Renders the mockup and the theme-CSS harness in headless Chrome, probes the
 * same elements in both, and reports any geometry/style drift.
 */
const { execFileSync } = require('child_process');
const fs = require('fs');
const path = require('path');
const { buildInjection } = require('./probe.js');

// Derived from this file's location so the theme can be moved freely.
const THEME = path.resolve(__dirname, '..');
const SITE = path.resolve(THEME, '..');
const WIDTH = process.argv[2] || '1440';

const CH = process.env.CHROME || [
  'C:/Program Files/Google/Chrome/Application/chrome.exe',
  'C:/Program Files (x86)/Google/Chrome/Application/chrome.exe',
].find((p) => fs.existsSync(p));

if (!CH) {
  console.error('Chrome not found. Set the CHROME env var to its path.');
  process.exit(2);
}
if (!fs.existsSync(path.join(SITE, 'index.html'))) {
  console.error(`index.html not found in ${SITE} — this test compares against the static mockup.`);
  process.exit(2);
}

function measure(file, tag) {
  const inject = buildInjection();
  const src = fs.readFileSync(path.join(SITE, file), 'utf8');
  const tmp = path.join(SITE, `__probe_${tag}.html`);
  fs.writeFileSync(tmp, src.replace('</head>', inject + '</head>'), 'utf8');

  const dom = execFileSync(CH, [
    '--headless=new', '--disable-gpu', '--allow-file-access-from-files',
    '--autoplay-policy=no-user-gesture-required', '--hide-scrollbars',
    `--window-size=${WIDTH},1000`, '--virtual-time-budget=8000',
    '--dump-dom', `file:///${tmp}`,
  ], { maxBuffer: 64 * 1024 * 1024, encoding: 'utf8', stdio: ['ignore', 'pipe', 'ignore'] });

  fs.unlinkSync(tmp);
  const m = dom.match(/<script type="application\/json" id="probe-result">([\s\S]*?)<\/script>/);
  if (!m) { throw new Error('probe did not run for ' + file); }
  return JSON.parse(m[1]);
}

// Build the harness first so this test is a single command.
require('child_process').execFileSync(process.execPath, [path.join(__dirname, 'build-harness.js')], { stdio: 'ignore' });

const a = measure('index.html', 'orig');
const b = measure('__harness.html', 'theme');

let checked = 0, drift = 0, missing = 0;
const report = [];

for (const sel of Object.keys(a)) {
  const x = a[sel], y = b[sel];
  if (!x && !y) { continue; }
  if (!x || !y) { missing++; report.push(`MISSING  ${sel} — ${!x ? 'absent in mockup' : 'absent in theme harness'}`); continue; }
  checked++;

  const diffs = [];
  // Geometry: allow 1px for sub-pixel rounding.
  for (const k of ['w', 'h', 'x']) {
    if (Math.abs(x[k] - y[k]) > 1) { diffs.push(`${k} ${x[k]} -> ${y[k]}`); }
  }
  for (const p of Object.keys(x.s)) {
    if (x.s[p] !== y.s[p]) { diffs.push(`${p} "${x.s[p]}" -> "${y.s[p]}"`); }
  }
  if (diffs.length) { drift++; report.push(`DRIFT    ${sel}\n           ${diffs.join('\n           ')}`); }
}

console.log(`viewport ${WIDTH}px — ${checked} elements compared`);
if (report.length) {
  console.log(report.join('\n'));
} else {
  console.log('no drift: theme CSS renders identically to the mockup');
}
console.log(`\n${checked - drift}/${checked} identical, ${drift} drifted, ${missing} missing`);

// Tidy the generated harness so it never gets mistaken for a real page.
const harness = path.join(SITE, '__harness.html');
if (fs.existsSync(harness)) { fs.unlinkSync(harness); }

process.exit(drift || missing ? 1 : 0);
