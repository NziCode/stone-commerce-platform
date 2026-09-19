/*!
 * About page behaviour (vanilla JS, no dependencies):
 *  1) reveal blocks as they scroll into view
 *  2) count the numbers up once, when they become visible
 * The stylesheet only hides revealable blocks while the `ab-js` class is on <html>,
 * so the page stays fully readable if this file never runs.
 */
(function () {
  'use strict';

  // this file is running — cancel the inline safety net that would un-hide everything
  if (window.__abFallback) { clearTimeout(window.__abFallback); }

  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var blocks = Array.prototype.slice.call(document.querySelectorAll('[data-ab-reveal]'));
  var isFa = (document.documentElement.lang || '').toLowerCase().indexOf('fa') === 0;

  var format = function (n) {
    return isFa ? n.toLocaleString('fa-IR', { useGrouping: false }) : String(n);
  };

  var countUp = function (el) {
    var target = parseInt(el.getAttribute('data-ab-count'), 10);
    if (isNaN(target) || reduce) { return; }

    var duration = 1400;
    var start = null;
    el.textContent = format(0);

    var step = function (ts) {
      if (start === null) { start = ts; }
      var p = Math.min((ts - start) / duration, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = format(Math.round(target * eased));
      if (p < 1) { window.requestAnimationFrame(step); }
    };

    window.requestAnimationFrame(step);
  };

  var show = function (block) {
    block.classList.add('is-in');
    Array.prototype.forEach.call(block.querySelectorAll('[data-ab-count]'), countUp);
  };

  if (!('IntersectionObserver' in window)) {
    blocks.forEach(show);
    return;
  }

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        show(entry.target);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.14, rootMargin: '0px 0px -6% 0px' });

  blocks.forEach(function (block) { observer.observe(block); });
})();
