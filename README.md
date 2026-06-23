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
- an **admin panel** (Filament) — 21 resources covering catalog, commerce, content, users/roles, and system configuration, with a custom-branded theme, live dashboard widgets, stats, charts, order tables, and a tabbed settings page

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
- a **custom database-backed translation loader** (`App\Translation\DatabaseLoader`) that serves UI strings (`messages` / `admin` groups, 500+ keys) from a cached, admin-editable `translations` table — with automatic fallback to lang files
- admin-managed active/default languages with a cached lookup layer
- per-locale slugs and default-locale-aware listing/search

### 🔐 Access Control
Two-tier access model:

**SuperUser** — a single privileged account whose password is stored exclusively in `.env` as a bcrypt hash — never in the database. Even with full DB access, the password cannot be recovered or cracked. SuperUser capabilities: language management, user role assignment, sensitive settings (payment gateway keys, SMTP password), and creating/deleting users.

**Admin / Editor / Sales** — role-based access via **Filament Shield** + **Spatie Permission**, with four seeded roles and granular, resource-level permissions.

### 🛒 Commerce & Order Workflows
- Cart → Checkout → Order → Payment, fully wired end to end
- **Iranian payment gateways** via `shetabit/multipay` (ZarinPal, IDPay, …)
- **International orders**: bank-transfer receipt upload + manual admin approval
- Order lifecycle, cancellation, and PDF export
- Coupons, wishlists, product reviews (with admin moderation), and newsletter

### 🎨 Frontend Design System
A redesigned storefront built around a shared design system (`theme-modern.css`): the brand's orange/navy palette refined with stone-toned neutrals, RTL-first, fully responsive with a mobile bottom navigation bar, touch-optimized Swiper sliders, and no arrow buttons on mobile.

### 📊 Admin Panel
A custom-branded Filament panel with a dark navy sidebar, live dashboard widgets (stats overview, revenue chart, product-status chart, latest orders table), quick-actions cards (pending orders / receipts / reviews / messages), a tabbed settings page (8 tabs: General, SEO, Social, Payment, SMTP, SMS, Contact, About), and a custom `admin-modern.css` polish layer.

### 🔍 SEO
Full SEO via `artesaos/seotools`: dynamic meta title/description, Open Graph, JSON-LD (Product schema on product pages, Article on posts), canonical URLs, Google Analytics (GA4), Google Tag Manager, Google Search Console verification — all configurable from the admin panel. Auto-generated `sitemap.xml` covering all active locales.

---

## 🚀 Core Business Modules

| Module | Description |
|--------|-------------|
| Product Catalog | Nested categories, SKU, pricing, status lifecycle, gallery |
| Dynamic Attributes | Reusable specs, admin-configurable, filterable |
| Commerce | Cart, checkout, orders, two payment paths (gateway + receipt) |
| CMS | Pages, sliders, menus, news, exhibitions — all multilingual |
| Auth | Login, register, forgot/reset password, email verify, profile |
| Reviews | Star rating, pending moderation, per-product display |
| Wishlist | Authenticated toggle, dedicated page |
| Newsletter | Subscribe, confirm page, unsubscribe via token |
| Contact | Form → admin inbox |
| SEO | Full meta, OG, JSON-LD, sitemap, robots.txt |
| Errors | Custom 401/403/404/419/429/500/503 pages |

---

## 🧱 Domain Model

`Language` · `Category` · `Product` · `ProductAttribute` · `ProductAttributeOption` · `ProductAttributeValue` · `Attribute` ·
`Cart` · `CartItem` · `Order` · `OrderItem` · `Payment` · `Coupon` · `Wishlist` · `Review` ·
`Page` · `Post` · `Event` · `Slider` · `Menu` · `MenuItem` · `Setting` · `SeoMeta` · `Redirect` ·
`ContactMessage` · `Newsletter` · `Translation` · `User`

---

## ⚙️ Technical Foundation

- ✅ **Laravel 12** + **Filament v3**
- ✅ **Filament Shield** + **Spatie Permission** (SuperUser + 4 roles)
- ✅ **Spatie Media Library** · **Spatie Activitylog** · **Spatie Laravel Backup**
- ✅ **Spatie Response Cache** · **Spatie Laravel Translatable** · **Spatie Laravel Sluggable**
- ✅ **Spatie Laravel Sitemap** · **Spatie Image Optimizer**
- ✅ **artesaos/seotools** (meta, OG, JSON-LD, GA4, GTM, canonical)
- ✅ **shetabit/multipay** (ZarinPal, IDPay and other Iranian gateways)
- ✅ **barryvdh/laravel-dompdf** (PDF invoices)
- ✅ **maatwebsite/excel** (admin exports)
- ✅ **biscolab/laravel-recaptcha** (form protection)
- ✅ **kalnoy/nestedset** (nested category trees)
- ✅ **laravel/horizon** (queue monitoring — installed)
- ✅ **mcamara/laravel-localization** (locale routing)

---

## 🌱 Seeders

| Seeder | Contents |
|--------|----------|
| `LanguageSeeder` | fa, en, ar, hi, it |
| `RolePermissionSeeder` | admin, editor, sales, customer roles with granular permissions |
| `AdminUserSeeder` | default admin account |
| `SuperUserSeeder` | SuperUser account — reads email from `.env`, sets unusable DB password |
| `SettingSeeder` | site-wide settings (general, SEO, social, payment, appearance) |
| `TranslationSeeder` | 500+ UI keys — `admin` + `messages` groups, all 5 languages |
| `CategorySeeder` | multilingual stone-category trees |
| `AttributeSeeder` | reusable product specification attributes |
| `ProductSeeder` | sample catalog data |
| `SliderSeeder` | homepage slider content |
| `MenuSeeder` | header/footer menu structure |
| `EventSeeder` | sample exhibitions |
| `PostSeeder` | 6 multilingual blog posts |
| `PageSeeder` | About, Certificates, Our Mines, Buying Guide, Payment Methods, Shipping |

---

## 🔑 SuperUser Setup

The SuperUser account's password is **never stored in the database**. It lives exclusively in `.env` as a bcrypt hash.

```bash
# 1. Generate the hash
php artisan superuser:hash

# 2. Add to .env
SUPER_USER_EMAIL=super@yourdomain.com
SUPER_USER_PASSWORD_HASH=$2y$12$...

# 3. Create the DB record
php artisan db:seed --class=SuperUserSeeder
```

SuperUser-only capabilities: language management, user role assignment, creating/deleting users.

---

## 🗺️ Development Roadmap

### ✅ Completed (~97%)

- [x] Full storefront (all pages, RTL-first, mobile-first, bottom nav)
- [x] Auth system (login, register, forgot/reset, verify, confirm)
- [x] Custom error pages (401/403/404/419/429/500/503)
- [x] Cart → Checkout → Order → Payment (online + receipt upload)
- [x] Wishlist, product reviews with moderation, coupons
- [x] Newsletter (subscribe, confirm page, unsubscribe)
- [x] Contact form with admin inbox
- [x] CMS pages (6 multilingual content pages)
- [x] News and exhibitions modules
- [x] Admin panel (21 resources, live dashboard, tabbed settings)
- [x] SuperUser system (.env-based, DB-independent password)
- [x] Full SEO (meta, OG, JSON-LD, sitemap, robots.txt, GA, GTM)
- [x] 5-language translation system (500+ keys, DB-backed, admin-editable)

### ❌ Remaining (~3%)

| Task | Priority |
|------|----------|
| Email notifications (order confirm, receipt approved, welcome) | 🔴 High |
| SMS notifications (Horizon job wiring) | 🟡 Medium |
| Production deployment (.env hardening, Supervisor, opcache) | 🔴 High |
| Image optimization (WebP, lazy load audit) | 🟢 Low |
| Order PDF download for customers | 🟢 Low |

---

## ✍️ Author

Developed and maintained by **NziCode**

---

## 🔒 License

Proprietary — All rights reserved.
