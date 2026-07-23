/**
 * Build a static harness that renders the mockup's markup through the THEME's
 * ported CSS (tokens.css + widgets.css + theme.css) with the .adl scope
 * wrappers the widgets emit. compare.js probes this against the original to
 * prove the port + namespacing didn't change the design.
 *
 * Paths are derived from this file's location, so moving the theme is safe.
 */
const fs = require('fs');
const path = require('path');

const THEME = path.resolve(__dirname, '..');   // …/addlar-theme
const SITE = path.resolve(THEME, '..');        // folder holding index.html
const OUT = path.join(SITE, '__harness.html');
// Relative path from the harness (written into SITE) to the theme.
const REL = path.relative(SITE, THEME).split(path.sep).join('/');

const html = fs.readFileSync(path.join(SITE, 'index.html'), 'utf8');

// Body markup only (keep the inline script so the page behaves the same).
let body = html.split('<body>')[1].split('</body>')[0];

// The journey rows carry per-row colours as inline custom properties. The
// widget emits the namespaced names, so the harness must too — otherwise the
// theme CSS (which reads --adl-ja/--adl-jt) finds nothing.
body = body.replace(/--ja:/g, '--adl-ja:').replace(/--jt:/g, '--adl-jt:');

// Wrap each top-level block in the .adl scope, exactly as the widgets do.
const OPEN = /^(<header\b|<footer\b|<section\b|<div class="mobnav"|<div class="trust")/;
const lines = body.split('\n');
const out = [];
let depth = 0;
let wrapping = false;
for (const line of lines) {
  if (!wrapping && OPEN.test(line)) {
    out.push('<div class="adl">');
    wrapping = true;
    depth = 0;
  }
  if (wrapping) {
    depth += (line.match(/<(header|footer|section|div)\b/g) || []).length;
    depth -= (line.match(/<\/(header|footer|section|div)>/g) || []).length;
  }
  out.push(line);
  if (wrapping && depth <= 0) {
    out.push('</div>');
    wrapping = false;
  }
}
body = out.join('\n');

const page = `<!DOCTYPE html>
<html lang="en" class="js">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ADDLAR — theme CSS harness</title>
<link rel="stylesheet" href="${REL}/assets/css/tokens.css">
<link rel="stylesheet" href="${REL}/assets/css/widgets.css">
<link rel="stylesheet" href="${REL}/assets/css/theme.css">
<link rel="stylesheet" href="${REL}/style.css">
</head>
<body>
${body}
</body>
</html>`;

fs.writeFileSync(OUT, page, 'utf8');
console.log('harness written:', OUT, fs.statSync(OUT).size, 'bytes');
console.log('.adl wrappers inserted:', (page.match(/<div class="adl">/g) || []).length);
