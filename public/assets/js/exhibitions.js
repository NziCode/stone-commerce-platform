/*!
 * Exhibitions page behaviour (vanilla JS, no dependencies):
 *  1) instant filter pills on the list page (links keep working without JS)
 *  2) accessible lightbox for the photo gallery on the detail page
 */
(function () {
  'use strict';

  /* ── 1) Filter pills ─────────────────────────────────────────────────── */
  var root = document.querySelector('[data-exh-filter]');
  if (root) {
    var pills = document.querySelectorAll('[data-exh-pill]');

    var apply = function (value) {
      root.setAttribute('data-exh-filter', value);
      pills.forEach(function (pill) {
        var active = pill.getAttribute('data-exh-pill') === value;
        pill.classList.toggle('is-active', active);
        pill.setAttribute('aria-pressed', active ? 'true' : 'false');
      });
    };

    pills.forEach(function (pill) {
      pill.addEventListener('click', function (e) {
        e.preventDefault();
        var value = pill.getAttribute('data-exh-pill');
        apply(value);
        try {
          var url = new URL(window.location.href);
          if (value === 'all') { url.searchParams.delete('filter'); } else { url.searchParams.set('filter', value); }
          window.history.replaceState(null, '', url.toString());
        } catch (err) { /* history API unavailable — filtering still works */ }
      });
    });
  }

  /* ── 2) Lightbox ─────────────────────────────────────────────────────── */
  var links = Array.prototype.slice.call(document.querySelectorAll('[data-exh-lightbox]'));
  if (!links.length) { return; }

  var labels = (document.querySelector('[data-exh-labels]') || document.body).dataset;
  var current = 0;
  var lastFocus = null;

  // Persian readers get Persian digits in the "3 / 35" counter, like the rest of the page
  var isFa = (document.documentElement.lang || '').toLowerCase().indexOf('fa') === 0;
  var fmt = function (n) { return isFa ? n.toLocaleString('fa-IR', { useGrouping: false }) : String(n); };

  var svg = function (path) {
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + path + '</svg>';
  };

  var box = document.createElement('div');
  box.className = 'exh-lb';
  box.setAttribute('role', 'dialog');
  box.setAttribute('aria-modal', 'true');
  box.innerHTML =
    '<button type="button" class="exh-lb-close" aria-label="' + (labels.exhClose || 'Close') + '">' + svg('<path d="M18 6 6 18M6 6l12 12"/>') + '</button>' +
    '<button type="button" class="exh-lb-prev" aria-label="' + (labels.exhPrev || 'Previous') + '">' + svg('<path d="m15 18-6-6 6-6"/>') + '</button>' +
    '<button type="button" class="exh-lb-next" aria-label="' + (labels.exhNext || 'Next') + '">' + svg('<path d="m9 18 6-6-6-6"/>') + '</button>' +
    '<div class="exh-lb-stage"><img alt=""></div>' +
    '<div class="exh-lb-bar"><p class="exh-lb-caption"></p><div class="exh-lb-count"></div></div>';
  document.body.appendChild(box);

  var img = box.querySelector('img');
  var caption = box.querySelector('.exh-lb-caption');
  var counter = box.querySelector('.exh-lb-count');
  var btnClose = box.querySelector('.exh-lb-close');

  var show = function (index) {
    current = (index + links.length) % links.length;
    var link = links[current];
    var text = link.getAttribute('data-caption') || '';
    img.src = link.getAttribute('href');
    img.alt = text;
    caption.textContent = text;
    counter.textContent = fmt(current + 1) + ' / ' + fmt(links.length);
    // warm the neighbours so next/prev feels instant
    [current + 1, current - 1].forEach(function (i) {
      var n = links[(i + links.length) % links.length];
      if (n) { (new Image()).src = n.getAttribute('href'); }
    });
  };

  var open = function (index) {
    lastFocus = document.activeElement;
    box.classList.add('is-open');
    document.body.classList.add('exh-lb-lock');
    show(index);
    btnClose.focus();
  };

  var close = function () {
    box.classList.remove('is-open');
    document.body.classList.remove('exh-lb-lock');
    img.removeAttribute('src');
    if (lastFocus && lastFocus.focus) { lastFocus.focus(); }
  };

  links.forEach(function (link, i) {
    link.addEventListener('click', function (e) {
      // let ctrl/cmd/middle-click open the file normally
      if (e.metaKey || e.ctrlKey || e.shiftKey || e.button === 1) { return; }
      e.preventDefault();
      open(i);
    });
  });

  box.querySelector('.exh-lb-prev').addEventListener('click', function () { show(current - 1); });
  box.querySelector('.exh-lb-next').addEventListener('click', function () { show(current + 1); });
  btnClose.addEventListener('click', close);
  box.addEventListener('click', function (e) {
    if (e.target === box || e.target.classList.contains('exh-lb-stage') || e.target.classList.contains('exh-lb-bar')) { close(); }
  });

  document.addEventListener('keydown', function (e) {
    if (!box.classList.contains('is-open')) { return; }
    var rtl = document.documentElement.dir === 'rtl';
    if (e.key === 'Escape') { close(); }
    else if (e.key === 'ArrowRight') { show(current + (rtl ? -1 : 1)); }
    else if (e.key === 'ArrowLeft') { show(current + (rtl ? 1 : -1)); }
    else if (e.key === 'Tab') {
      // keep focus inside the dialog
      var focusable = box.querySelectorAll('button');
      var first = focusable[0], last = focusable[focusable.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }
  });

  // swipe on touch screens
  var startX = null;
  box.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
  box.addEventListener('touchend', function (e) {
    if (startX === null) { return; }
    var dx = e.changedTouches[0].clientX - startX;
    startX = null;
    if (Math.abs(dx) < 50) { return; }
    var rtl = document.documentElement.dir === 'rtl';
    show(current + ((dx < 0) !== rtl ? 1 : -1));
  }, { passive: true });
})();
