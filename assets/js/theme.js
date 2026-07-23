/**
 * ADDLAR theme behaviours.
 *
 * Ported verbatim from the approved static mockup, with two changes:
 *  - everything is guarded so a missing section never throws;
 *  - the product finder reads its data from [data-finder] instead of a literal,
 *    so the client can edit categories/codes in Elementor.
 *
 * Re-initialised on Elementor's frontend hook so widgets work in the editor.
 */
(function () {
	'use strict';

	/* ------------------------------------------------------------- header */
	function initHeader() {
		var hdr = document.getElementById('hdr');
		if (!hdr) { return; }
		var onScroll = function () {
			hdr.classList.toggle('scrolled', window.scrollY > 20);
		};
		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });
	}

	/* --------------------------------------------------------- mobile nav */
	function initMobileNav() {
		var burger = document.getElementById('burger');
		var mob = document.getElementById('mobnav');
		if (!burger || !mob || burger.dataset.bound) { return; }
		burger.dataset.bound = '1';

		function setMenu(open) {
			mob.classList.toggle('open', open);
			burger.classList.toggle('on', open);
			burger.setAttribute('aria-expanded', String(open));
		}

		burger.addEventListener('click', function (e) {
			e.stopPropagation();
			setMenu(!mob.classList.contains('open'));
		});
		mob.querySelectorAll('a').forEach(function (a) {
			a.addEventListener('click', function () { setMenu(false); });
		});
		document.addEventListener('click', function (e) {
			if (mob.classList.contains('open') && !mob.contains(e.target)) { setMenu(false); }
		});
		window.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') { setMenu(false); }
		});
	}

	/* ------------------------------------------------------------ reveals */
	function initReveal() {
		var els = document.querySelectorAll('.reveal:not(.in)');
		if (!els.length) { return; }
		if (!('IntersectionObserver' in window)) {
			els.forEach(function (el) { el.classList.add('in'); });
			return;
		}
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting) {
					e.target.classList.add('in');
					io.unobserve(e.target);
				}
			});
		}, { threshold: 0.12 });
		els.forEach(function (el) { io.observe(el); });
	}

	/* ----------------------------------------------------------- count-up */
	function format(n, el) {
		var s = el.dataset.comma ? n.toLocaleString('en-US') : String(n);
		var pre = el.dataset.prefix || '';
		var suf = el.dataset.suffix ? '<small>' + el.dataset.suffix + '</small>' : '';
		return pre + s + suf;
	}

	function countUp(el) {
		var target = parseInt(el.dataset.count, 10);
		if (isNaN(target)) { return; }
		var n = 0;
		var steps = 44;
		var inc = Math.max(1, Math.ceil(target / steps));
		var timer = setInterval(function () {
			n += inc;
			if (n >= target) { n = target; clearInterval(timer); }
			el.innerHTML = format(n, el);
		}, 26);
	}

	function initCounters() {
		var stats = document.querySelectorAll('.nstat .n[data-count]:not([data-counted])');
		if (!stats.length) { return; }
		if (!('IntersectionObserver' in window)) {
			stats.forEach(function (el) { el.dataset.counted = '1'; countUp(el); });
			return;
		}
		var sio = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting) {
					e.target.dataset.counted = '1';
					countUp(e.target);
					sio.unobserve(e.target);
				}
			});
		}, { threshold: 0.5 });
		stats.forEach(function (el) { sio.observe(el); });
	}

	/* ------------------------------------------------------------- finder */
	function initFinder() {
		document.querySelectorAll('.finder[data-finder]').forEach(function (root) {
			if (root.dataset.bound) { return; }
			root.dataset.bound = '1';

			var data;
			try {
				data = JSON.parse(root.getAttribute('data-finder'));
			} catch (err) {
				return;
			}
			if (!data || !Object.keys(data).length) { return; }

			var catsEl = root.querySelector('[data-role="cats"]');
			var subsEl = root.querySelector('[data-role="subs"]');
			var msgEl = root.querySelector('[data-role="msg"]');
			var pillsEl = root.querySelector('[data-role="pills"]');
			if (!catsEl || !subsEl || !msgEl || !pillsEl) { return; }

			var strings = {
				chooseCat: root.dataset.msgCat || 'Choose a category to begin.',
				chooseSub: root.dataset.msgSub || 'Now choose a sub-category.',
				products: root.dataset.msgProducts || 'products',
				product: root.dataset.msgProduct || 'product'
			};
			var curCat = null;

			function mkBtn(label, onClick) {
				var b = document.createElement('button');
				b.className = 'fopt';
				b.type = 'button';
				b.textContent = label;
				b.addEventListener('click', function () { onClick(b); });
				return b;
			}

			function renderPills(codes) {
				pillsEl.innerHTML = '';
				codes.forEach(function (code) {
					var s = document.createElement('span');
					s.className = 'pill';
					// Numeric codes are ADDLAR packages; lettered ones (KC…) stand alone.
					if (/^\d+$/.test(code)) {
						s.innerHTML = 'ADDLAR <b></b>';
						s.querySelector('b').textContent = code;
					} else {
						s.innerHTML = '<b></b>';
						s.querySelector('b').textContent = code;
					}
					pillsEl.appendChild(s);
				});
			}

			function selectSub(sub, btn) {
				subsEl.querySelectorAll('.fopt').forEach(function (x) { x.classList.remove('on'); });
				btn.classList.add('on');
				var codes = data[curCat][sub] || [];
				var word = codes.length === 1 ? strings.product : strings.products;
				msgEl.innerHTML = '<div class="fmsg"><b></b> <span></span></div>';
				msgEl.querySelector('b').textContent = codes.length + ' ' + word;
				msgEl.querySelector('span').textContent = 'in ' + sub + '.';
				renderPills(codes);
			}

			function selectCat(cat, btn) {
				curCat = cat;
				catsEl.querySelectorAll('.fopt').forEach(function (x) { x.classList.remove('on'); });
				btn.classList.add('on');
				subsEl.innerHTML = '';
				Object.keys(data[cat]).forEach(function (sub) {
					subsEl.appendChild(mkBtn(sub, function (b) { selectSub(sub, b); }));
				});
				msgEl.innerHTML = '<div class="fmsg"></div>';
				msgEl.querySelector('.fmsg').textContent = strings.chooseSub;
				pillsEl.innerHTML = '';
			}

			catsEl.innerHTML = '';
			Object.keys(data).forEach(function (cat) {
				catsEl.appendChild(mkBtn(cat, function (b) { selectCat(cat, b); }));
			});
			subsEl.innerHTML = '<div class="fmsg">' + '</div>';
			subsEl.querySelector('.fmsg').textContent = strings.chooseCat;
		});
	}

	/* --------------------------------------------------------------- boot */
	function init() {
		initHeader();
		initMobileNav();
		initReveal();
		initCounters();
		initFinder();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}

	// Elementor editor: re-run when a widget is (re)rendered in the preview.
	window.addEventListener('elementor/frontend/init', function () {
		if (window.elementorFrontend && window.elementorFrontend.hooks) {
			window.elementorFrontend.hooks.addAction('frontend/element_ready/global', init);
		}
	});
})();
