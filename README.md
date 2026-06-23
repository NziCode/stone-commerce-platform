<div align="center">

# Stone Commerce Platform
### Enterprise Stone Trading & E-Commerce Platform

A full-stack platform for **stone catalog management, single-item product modeling, multilingual commerce, and a custom-branded admin panel**,
built with **Laravel 12**, **Filament v3**, and a modular, production-oriented architecture.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-3.x-FACC15?style=for-the-badge&logoColor=black)](https://filamentphp.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Languages](https://img.shields.io/badge/Languages-5-2563EB?style=for-the-badge)](#-multilingual-content-system)
[![Status](https://img.shields.io/badge/Status-Active%20Development-16A34A?style=for-the-badge)](#-development-roadmap)
[![Maintained By](https://img.shields.io/badge/Maintained%20By-NziCode-0F172A?style=for-the-badge)](https://github.com/NziCode)

<br>

> A working storefront and admin system for selling unique natural-stone items — from catalog to checkout to delivery — in five languages.

</div>

---

## ✨ Overview

**stone-commerce-platform** is a complete backend **and** customer-facing system for the **stone trading and e‑commerce industry**.

The platform spans two fully built surfaces:

- a **public storefront** — catalog, search, cart, checkout, payments, orders, wishlist, reviews, news, exhibitions, CMS pages, error pages, and SEO — with a custom-designed modern UI
- an **admin panel** (Filament) — 21 resources covering catalog, commerce, content, users/roles, and system configuration, with a custom-branded theme, live dashboard widgets, stats, charts, and order tables

Unlike traditional e‑commerce systems, this platform models **unique stone items**, where each product represents a **specific stone block or slab** — not a quantity-based inventory item. Availability is a lifecycle state (`available` / `reserved` / `sold`), not a stock count.

---

## 🎯 Project Goals

- a structured, multilingual digital catalog for natural stone
- a self-serve storefront covering the full buyer journey (browse → cart → checkout → pay → track)
- an admin experience editors and sales staff can actually use day-to-day
- support for both Iranian and international buyers/payment flows
- a maintainable, modular Laravel/Filament codebase built for long-term extension

---

## 🛡️ Usage Notice

> This repository is published primarily for **development and portfolio demonstration**.
>
> Unauthorized redistribution or commercial use of this project without permission is not allowed.

---

## 🏛️ Platform Highlights

### Unique Stone Product Modeling
Each product carries structured metadata: category, SKU, price (local + USD), status, featured/new flags, primary category, plus dynamic technical attributes (stone type, finish, grade, thickness, dimensions, origin, and more) via a schema-less attribute engine.

### 🌍 Multilingual Content System (5 languages)
- Persian (`fa`), English (`en`), Arabic (`ar`), Hindi (`hi`), Italian (`it`)
- RTL/LTR layouts on both the storefront and the admin panel
- `spatie/laravel-translatable` for translatable model fields
- a **custom database-backed translation loader** (`App\Translation\DatabaseLoader`) that serves UI strings (`messages` / `admin` groups, 400+ keys) from a cached, admin-editable `translations` table — with automatic fallback to lang files
- admin-managed active/default languages with a cached lookup layer
- per-locale slugs and default-locale-aware listing/search

### 🔐 Access Control
Role-based access via **Filament Shield** + **Spatie Permission**, with four seeded roles — `admin`, `editor`, `sales`, `customer` — and granular, resource-level permissions.

### 🛒 Commerce & Order Workflows
- Cart → Checkout → Order → Payment, fully wired end to end
- **Iranian payment gateways** via `shetabit/multipay` (ZarinPal, IDPay, …)
- **International orders**: bank-transfer receipt upload + manual admin approval
- Order lifecycle, cancellation, and PDF export
- Coupons, wishlists, product reviews (with admin moderation), and newsletter

### 🎨 Frontend Design System
A redesigned storefront built around a shared design system (`theme-modern.css`): the brand's orange/navy palette refined with stone-toned neutrals, RTL-first, fully responsive with a mobile bottom navigation bar, touch-optimized Swiper sliders, and a floating search/cart/profile bottom nav on mobile.

### 📊 Admin Panel
A custom-branded Filament panel with a dark navy sidebar, live dashboard widgets (stats overview, revenue chart, product-status chart, latest orders table), brand colors wired to site settings, and a `admin-modern.css` polish layer — no custom Vite build required.

### 🔍 SEO
Full SEO implementation via `artesaos/seotools`: dynamic meta title/description, Open Graph, JSON-LD (Product schema on product pages, Article on posts), canonical URLs, Google Analytics, Google Tag Manager, Google Search Console verification — all configurable from the admin panel. Auto-generated `sitemap.xml` covering products, categories, posts, events, and pages in all active locales.

---

## 🚀 Core Business Modules

### 1) Product Information Management
Categories (nested, via `kalnoy/nestedset`), products, multilingual descriptions, SEO-friendly slugs, technical metadata, featured items, and media (image + thumbnail + gallery + video) via Spatie Media Library.

### 2) Dynamic Attribute Engine
Reusable attributes, configurable options, product-specific values, filterable metadata — no rigid schema required to add a new spec.

### 3) Single-Item Product Logic
Each listing is a unique item with a lifecycle status (`available` / `reserved` / `sold`) rather than a stock count.

### 4) Dynamic Language Management
Enable/disable languages from the admin panel, set a default, cache active locales, and auto-generate translation form fields for every translatable resource.

### 5) CMS Layer
Dynamic pages (About, Contact, Buying Guide, Payment Methods, Shipping, Certificates, Our Mines), homepage sliders/banners, admin-managed header/footer menus, news (blog) module, exhibitions/events module — all multilingual with rich content.

### 6) Commerce Layer
Cart, checkout, order lifecycle, two payment paths (online gateway + receipt upload), coupons, wishlists, product reviews with pending/approved/rejected moderation, contact-form inbox, newsletter with subscription/unsubscription flow.

### 7) Storefront Experience
Home, product catalog (grid/list, filters, sort), product detail with gallery + tabs + star-rating reviews, categories, cart, checkout, orders, payment, wishlist, posts, events, search, contact, CMS pages, auth pages (login, register, forgot/reset password, verify email) — all with a unified design system, proper error pages (401/403/404/419/429/500/503), and SEO meta on every page.

### 8) Admin Experience
21 Filament resources, 4 live dashboard widgets, custom-branded theme (dark sidebar, stats cards, branded login page), permission-aware navigation.

---

## 🧱 Domain Model

Implemented entities:

`Language` · `Category` · `Product` · `ProductAttribute` · `ProductAttributeOption` · `ProductAttributeValue` · `Attribute` ·
`Cart` · `CartItem` · `Order` · `OrderItem` · `Payment` · `Coupon` · `Wishlist` · `Review` ·
`Page` · `Post` · `Event` · `Slider` · `Menu` · `MenuItem` · `Setting` · `SeoMeta` · `Redirect` ·
`ContactMessage` · `Newsletter` · `Translation` · `User`

---

## ⚙️ Technical Foundation

- ✅ **Laravel 12**
- ✅ **Filament v3** Admin Panel (21 resources, 4 dashboard widgets, custom theme)
- ✅ **Filament Shield** + **Spatie Permission** (role-based access control)
- ✅ **Spatie Media Library** (images, galleries, video)
- ✅ **Spatie Activitylog** · **Spatie Laravel Backup** · **Spatie Response Cache**
- ✅ **Spatie Laravel Translatable** + **custom DB-backed UI translation loader** (400+ keys, 5 languages)
- ✅ **Spatie Laravel Sluggable** · **Spatie Laravel Sitemap** · **Spatie Image Optimizer**
- ✅ **artesaos/seotools** (meta, OG, JSON-LD, GA, GTM, canonical)
- ✅ **shetabit/multipay** for Iranian payment gateways
- ✅ **barryvdh/laravel-dompdf** (order/invoice PDFs)
- ✅ **maatwebsite/excel** (admin exports)
- ✅ **biscolab/laravel-recaptcha** (form protection)
- ✅ **kalnoy/nestedset** (nested category trees)
- ✅ **laravel/horizon** (queue monitoring — installed, jobs pending)
- ✅ **mcamara/laravel-localization** (locale routing)
- ✅ A fully redesigned **storefront UI** (RTL-first, mobile-first, bottom nav, touch sliders)
- ✅ A custom-branded **admin panel theme** (dark sidebar, live widgets)

---

## 🌱 Seeders

| Seeder | Contents |
|--------|----------|
| `LanguageSeeder` | fa, en, ar, hi, it |
| `TranslationSeeder` | 400+ UI keys — `messages` + `admin` groups, all 5 languages |
| `RolePermissionSeeder` | admin, editor, sales, customer roles with granular permissions |
| `AdminUserSeeder` | default admin account |
| `SettingSeeder` | site-wide settings (general, SEO, social, payment, appearance) |
| `CategorySeeder` | multilingual stone-category trees |
| `AttributeSeeder` | reusable product specification attributes |
| `ProductSeeder` | sample catalog data |
| `SliderSeeder` | homepage slider content |
| `MenuSeeder` | header/footer menu structure |
| `EventSeeder` | sample exhibitions |
| `PostSeeder` | 6 multilingual blog posts (stone industry topics) |
| `PageSeeder` | About, Certificates, Our Mines, Buying Guide, Payment Methods, Shipping |

```bash
php artisan db:seed
# or individually:
php artisan db:seed --class=TranslationSeeder
php artisan lang:generate
php artisan sitemap:generate
```

---

## 🧰 Technology Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12 |
| Admin Panel | Filament v3 (custom-branded theme) |
| Frontend | Blade + custom CSS design system (RTL-first, mobile-first) |
| Language | PHP 8.2+ |
| Authorization | Filament Shield + Spatie Permission |
| Media | Spatie Media Library + Spatie Image Optimizer |
| Translations | Spatie Laravel Translatable + custom DB-backed UI translation loader |
| SEO | artesaos/seotools + Spatie Sitemap + robots.txt |
| Logging | Spatie Activitylog |
| Backups | Spatie Laravel Backup |
| Caching | Spatie Response Cache + Redis |
| Slugs | Spatie Laravel Sluggable |
| Category Trees | kalnoy/nestedset |
| Queues | Laravel Horizon |
| Locale Routing | mcamara/laravel-localization |
| SMS | Kavenegar / Melipayamak (configured, jobs pending) |
| Email | SMTP / SES (configured, Mailable classes pending) |
| Payments (IR) | ZarinPal / IDPay via shetabit/multipay |
| Payments (Intl.) | Receipt upload + manual approval |
| PDF | barryvdh/laravel-dompdf |
| Exports | maatwebsite/excel |
| Spam Protection | Google reCAPTCHA |
| Database | MySQL / MariaDB |

---

## 🗺️ Development Roadmap

### ✅ Completed

- [x] Laravel 12 + Filament v3 setup
- [x] Role-based access control (Shield + Spatie Permission)
- [x] 21 Filament admin resources
- [x] Live admin dashboard (stats, charts, order table)
- [x] Custom-branded admin theme
- [x] Nested categories + dynamic attribute engine
- [x] Single-item product lifecycle
- [x] Full storefront redesign (all pages, RTL-first, mobile-first)
- [x] Mobile UX overhaul (bottom nav, touch sliders, no arrow buttons)
- [x] Cart → Checkout → Order → Payment (online + receipt)
- [x] Wishlist, product reviews (with moderation), coupons
- [x] Auth pages (login, register, forgot/reset password, verify, confirm)
- [x] Profile, orders, wishlist account pages
- [x] CMS pages (About, Certificates, Mines, Buying Guide, Payment, Shipping)
- [x] News (blog) module with sidebar, related posts, share buttons
- [x] Exhibitions/events module
- [x] Newsletter (subscribe + confirm page + unsubscribe)
- [x] Contact form with admin inbox
- [x] Custom error pages (401/403/404/419/429/500/503)
- [x] Full SEO (meta, OG, JSON-LD, canonical, GA, GTM, sitemap, robots.txt)
- [x] 5-language translation system (DB-backed, admin-editable, 400+ keys)
- [x] SVG placeholder images for all model fallbacks
- [x] `storage:link` friendly media setup

### 🔄 In Progress / Next

- [ ] Email notifications (order confirmed, receipt approved, password reset)
- [ ] SMS notifications (Kavenegar/Melipayamak job wiring)
- [ ] Production deployment (`.env` hardening, Supervisor, opcache)
- [ ] Image optimization pass (WebP conversion, lazy loading audit)

### 📋 Backlog

- [ ] Advanced product filtering (price range, attribute filters)
- [ ] Order PDF invoice download for customers
- [ ] Admin bulk actions (export orders, bulk status update)
- [ ] Multi-currency price display
- [ ] Google reCAPTCHA activation on contact/newsletter forms

---

## 📌 Notes

The multilingual layer — both content (`spatie/laravel-translatable`) and UI strings (the custom `Translation` model + `DatabaseLoader`) — is designed to stay flexible as new languages are added.

The `TranslationSeeder` and `resources/lang/*/messages.php` files are always kept in sync: every new key added to the seeder is also added to all 5 lang files as a fallback.

Security-sensitive files (`.env`, credentials) are excluded from version control.

---

## ✍️ Author

Developed and maintained by **NziCode**

---

## 🔒 License

Proprietary — All rights reserved.
