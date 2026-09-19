/* ============================================================
   Stone Commerce — Mobile UX enhancements
   Loaded after main.js. Patches Swiper instances and adds
   mobile-specific interactions without touching the legacy file.
   The bottom navigation itself is server-rendered
   (resources/views/front/layouts/bottom-nav.blade.php); this file
   only wires up its search tab and keyboard behaviour.
   ============================================================ */

(function () {
    'use strict';

    const isMobile = () => window.innerWidth <= 767;

    /* ── 1. Enhance ALL Swiper instances with touch-friendly settings ── */
    document.addEventListener('DOMContentLoaded', function () {

        /* Patch every .swiper-container that Swiper has already initialised */
        document.querySelectorAll('.swiper-container').forEach(function (el) {
            const sw = el.swiper;
            if (!sw) return;
            /* Enable grab cursor and improve touch feel */
            sw.params.grabCursor = true;
            sw.params.touchRatio = 1;
            sw.params.touchAngle = 45;
            sw.params.resistance = true;
            sw.params.resistanceRatio = 0.65;
            sw.update();
        });

        /* ── 2. Pagination bullets on mobile (replace custom with bullets) ── */
        if (isMobile()) {
            document.querySelectorAll('.swiper-container').forEach(function (el) {
                const sw = el.swiper;
                if (!sw || !sw.pagination || !sw.pagination.el) return;
                /* If no visible bullets exist, inject a simple dot strip */
                if (!el.querySelector('.swiper-pagination-bullet')) {
                    sw.params.pagination.type = 'bullets';
                    sw.params.pagination.clickable = true;
                    sw.pagination.init();
                    sw.pagination.render();
                    sw.pagination.update();
                }
            });
        }

        /* ── 3. Hero finder: auto-focus with smooth scroll on mobile ── */
        const finderInput = document.querySelector('.mt-finder input[type="search"]');
        if (finderInput && isMobile()) {
            finderInput.addEventListener('focus', function () {
                setTimeout(function () {
                    finderInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);
            });
        }

        /* ── 4. Category chips: horizontal scroll on mobile (no wrap) ── */
        const chips = document.querySelector('.mt-finder-chips');
        if (chips && isMobile()) {
            chips.style.flexWrap = 'nowrap';
            chips.style.overflowX = 'auto';
            chips.style.paddingBottom = '4px';
            chips.style.webkitOverflowScrolling = 'touch';
            chips.style.scrollbarWidth = 'none';
        }

        /* ── 5. Product cards: full-width swipe hint (gentle shadow on edges) ── */
        document.querySelectorAll('.mt-pcard').forEach(function (card) {
            card.addEventListener('touchstart', function () {
                this.style.boxShadow = 'var(--shadow)';
            }, { passive: true });
            card.addEventListener('touchend', function () {
                this.style.boxShadow = '';
            }, { passive: true });
        });

        /* ── 6. Sticky header: shrink logo on scroll to save vertical space ── */
        const header = document.querySelector('.mt-header');
        const logo   = document.querySelector('.mt-logo img');
        if (header && isMobile()) {
            window.addEventListener('scroll', function () {
                const current = window.scrollY;
                if (current > 60) {
                    header.style.transition = 'padding .25s ease';
                    if (logo) logo.style.maxHeight = '28px';
                } else {
                    if (logo) logo.style.maxHeight = '';
                }
            }, { passive: true });
        }

        initSearchSheet();
        initKeyboardAwareNav();
    });

    /* ── 7. Bottom-nav search tab → full-width search sheet ────────────────
       The old version toggled the header's desktop dropdown, which is hidden on
       phones and was closed again by the header's own outside-click handler. */
    function initSearchSheet() {
        const sheet   = document.getElementById('mtSearchSheet');
        const trigger = document.getElementById('mtBnavSearch');
        if (!sheet || !trigger) return;

        const root  = document.documentElement;
        const input = sheet.querySelector('input[type="search"]');

        function open() {
            sheet.classList.add('open');
            root.classList.add('mt-no-scroll');
            trigger.setAttribute('aria-expanded', 'true');
            /* focus after the panel is painted so mobile browsers raise the keyboard */
            setTimeout(function () {
                if (!input) return;
                input.focus({ preventScroll: true });
                input.select();
            }, 60);
        }

        function close() {
            sheet.classList.remove('open');
            root.classList.remove('mt-no-scroll');
            trigger.setAttribute('aria-expanded', 'false');
            if (input) input.blur();
        }

        trigger.addEventListener('click', function (e) {
            /* on phones the tab opens the sheet; the href stays as the no-JS fallback */
            if (!isMobile()) return;
            e.preventDefault();
            sheet.classList.contains('open') ? close() : open();
        });

        sheet.querySelectorAll('[data-mt-search-close]').forEach(function (el) {
            el.addEventListener('click', close);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && sheet.classList.contains('open')) close();
        });

        /* rotating the phone / resizing to a wide screen must not leave the page locked */
        window.addEventListener('resize', function () {
            if (!isMobile() && sheet.classList.contains('open')) close();
        });

        /* coming back with the browser's back button (bfcache) */
        window.addEventListener('pageshow', close);
    }

    /* ── 8. Hide the bottom bar while the on-screen keyboard is up ───────── */
    function initKeyboardAwareNav() {
        const root = document.documentElement;
        const isField = (el) => el && el.matches && el.matches(
            'input:not([type="checkbox"]):not([type="radio"]):not([type="button"]):not([type="submit"]):not([type="range"]), textarea, select'
        );

        document.addEventListener('focusin', function (e) {
            if (isMobile() && isField(e.target)) root.classList.add('mt-kbd-open');
        });
        document.addEventListener('focusout', function () {
            /* wait one tick: focus may just be moving to another field */
            setTimeout(function () {
                if (!isField(document.activeElement)) root.classList.remove('mt-kbd-open');
            }, 50);
        });
    }

    /* ── 9. Smooth card press feedback (touch) ── */
    document.addEventListener('touchstart', function (e) {
        const target = e.target.closest('.mt-cat, .mt-btn, .mt-post, .mt-pcard');
        if (target) target.style.opacity = '.88';
    }, { passive: true });
    document.addEventListener('touchend', function (e) {
        const target = e.target.closest('.mt-cat, .mt-btn, .mt-post, .mt-pcard');
        if (target) target.style.opacity = '';
    }, { passive: true });

})();
