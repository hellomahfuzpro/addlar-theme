/** Selectors + computed properties compared between the mockup and the theme. */
const SELECTORS = [
  'header', '.nav', '.brand img', '.navlinks a', '.btn.btn-red',
  '.hero', '.hero-inner', '.hero h1', '.hero .lead', '.hero-btns',
  '.trust', '.trust .item',
  '#about', '.about-grid', '.about-img', '.about-feats', '.afeat',
  '#journey', '.jrny', '.jr', '.jhex', '.jhin', '.jmeta .num', '.jline',
  '#why', '.wrow', '.imgnum', '.wtxt h3',
  '#products', '.prod-grid', '.pcard', '.pcard .imgwrap', '.pcard-comp',
  '#packages', '.pkg-grid', '.pkg', '.pkg .phex',
  '#applications', '.appwrap', '.appvid', '.applist', '.appitem', '.beats', '.beat',
  '#numbers', '.numgrid', '.nstat', '.nstat .n',
  '#finder', '.finder', '.fcols', '.fcol',
  '#insights', '.li-grid', '.licard', '.licard .imgwrap', '.licard .body', '.li-follow',
  '#acea', '.cta .wrap', '.cta .map',
  'footer', '.foot-grid', '.foot-col h5', '.soc', '.foot-bottom',
];

const PROPS = ['display', 'gridTemplateColumns', 'fontSize', 'fontWeight', 'color',
  'backgroundColor', 'padding', 'margin', 'borderTopWidth', 'borderBottomWidth', 'maxWidth'];

/** Built as a string so it can be injected into the page. */
function buildInjection() {
  return `<script>
(function () {
  var SELECTORS = ${JSON.stringify(SELECTORS)};
  var PROPS = ${JSON.stringify(PROPS)};
  function probe() {
    var out = {};
    for (var i = 0; i < SELECTORS.length; i++) {
      var sel = SELECTORS[i];
      var el = document.querySelector(sel);
      if (!el) { out[sel] = null; continue; }
      var r = el.getBoundingClientRect();
      var cs = getComputedStyle(el);
      var styles = {};
      for (var j = 0; j < PROPS.length; j++) { styles[PROPS[j]] = cs[PROPS[j]]; }
      out[sel] = {
        w: Math.round(r.width), h: Math.round(r.height),
        x: Math.round(r.left), y: Math.round(r.top + window.scrollY),
        s: styles
      };
    }
    return out;
  }
  function run() {
    setTimeout(function () {
      var el = document.createElement('script');
      el.type = 'application/json';
      el.id = 'probe-result';
      el.textContent = JSON.stringify(probe());
      document.body.appendChild(el);
    }, 700);
  }
  if (document.readyState === 'complete') { run(); }
  else { window.addEventListener('load', run); }
})();
</script>`;
}

module.exports = { buildInjection, SELECTORS, PROPS };
