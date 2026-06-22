/* ============================================================
   EN Trading Group — Mobile UX enhancements
   Loaded after main.js. Patches Swiper instances and adds
   mobile-specific interactions without touching the legacy file.
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
            let lastScroll = 0;
            window.addEventListener('scroll', function () {
                const current = window.scrollY;
                if (current > 60) {
                    header.style.transition = 'padding .25s ease';
                    if (logo) logo.style.maxHeight = '28px';
                } else {
                    if (logo) logo.style.maxHeight = '';
                }
                lastScroll = current;
            }, { passive: true });
        }

        /* ── 7. Floating bottom nav (mobile only) ── */
        if (isMobile()) {
            const nav = document.createElement('nav');
            nav.id  = 'mt-bottom-nav';
            nav.innerHTML = `
                <a href="/" class="mt-bnav-item${window.location.pathname === '/' || window.location.pathname.endsWith('/fa') || window.location.pathname.endsWith('/en') ? ' active' : ''}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                    <span data-i18n="home">خانه</span>
                </a>
                <a href="javascript:void(0)" class="mt-bnav-item" id="mobileSearchBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <span data-i18n="search">جستجو</span>
                </a>
                <a href="/cart" class="mt-bnav-item" style="position:relative">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    <span data-i18n="cart">سبد</span>
                </a>
                <a href="/profile" class="mt-bnav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span data-i18n="profile">حساب</span>
                </a>
            `;
            document.body.appendChild(nav);

            /* Wire up bottom-nav search to open the header search */
            document.getElementById('mobileSearchBtn').addEventListener('click', function () {
                const box = document.getElementById('mtSearchBox');
                const btn = document.getElementById('searchToggleBtn');
                if (box) {
                    box.classList.toggle('open');
                    if (box.classList.contains('open')) {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        setTimeout(function () {
                            box.querySelector('input')?.focus();
                        }, 400);
                    }
                } else if (btn) {
                    btn.click();
                }
            });

            /* Add body bottom padding so last content isn't hidden behind nav */
            document.body.style.paddingBottom = '68px';

            /* Highlight active nav item */
            const path = window.location.pathname;
            nav.querySelectorAll('.mt-bnav-item[href]').forEach(function (a) {
                if (a.getAttribute('href') !== 'javascript:void(0)' && path.includes(a.getAttribute('href').replace(/^.*\//, '/'))) {
                    a.classList.add('active');
                }
            });
        }
    });

    /* ── 8. Smooth card press feedback (touch) ── */
    document.addEventListener('touchstart', function (e) {
        const target = e.target.closest('.mt-cat, .mt-btn, .mt-post, .mt-pcard');
        if (target) target.style.opacity = '.88';
    }, { passive: true });
    document.addEventListener('touchend', function (e) {
        const target = e.target.closest('.mt-cat, .mt-btn, .mt-post, .mt-pcard');
        if (target) target.style.opacity = '';
    }, { passive: true });

})();
