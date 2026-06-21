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

The platform now spans two fully built surfaces:

- a **public storefront** — catalog, search, cart, checkout, payments, orders, wishlist, reviews, news, exhibitions, and CMS pages, with a custom-designed UI
- an **admin panel** (Filament) — 20 resources covering catalog, commerce, content, users/roles, and system configuration, with a custom-branded theme

Unlike traditional e‑commerce systems, this platform models **unique stone items**, where each product represents a **specific stone block or slab** — not a quantity-based inventory item. Availability is a lifecycle state (`available` / `reserved` / `sold`), not a stock count.

---

## 🎯 Project Goals

- a structured, multilingual digital catalog for natural stone
- a self-serve storefront covering the full buyer journey (browse → cart → checkout → pay → track)
- an admin experience editors and sales staff can actually use day-to-day
- support for both Iranian and international buyers/payment flows
- a maintainable, modular Laravel/Filament codebase built for long-term extension

Key design principles: clarity, scalability, maintainability, modular development, long-term extensibility.

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
- `spatie/laravel-translatable` for translatable model fields (name, description, location, value, slug, …)
- a **custom database-backed translation loader** (`App\Translation\DatabaseLoader`) that serves UI strings (`messages` / `admin` groups) from a cached, admin-editable `translations` table — with automatic fallback to the language files if the database is empty
- admin-managed active/default languages with a cached `Language` lookup layer
- per-locale slugs and default-locale-aware listing/search

### 🔐 Access Control
Role-based access via **Filament Shield** + **Spatie Permission**, with four seeded roles — `admin`, `editor`, `sales`, `customer` — and granular, resource-level permissions (view/create/edit/delete/manage/export per module).

### 🛒 Commerce & Order Workflows
- Cart → Checkout → Order → Payment, fully wired end to end
- **Iranian payment gateways** via `shetabit/multipay` (ZarinPal, IDPay, …)
- **International orders**: bank-transfer receipt upload + manual admin approval
- Order lifecycle management, cancellation, and PDF export (`barryvdh/laravel-dompdf`)
- Coupons, wishlists, product reviews, and a newsletter subscription flow
- Contact-form inbox with admin replies

### 🧾 Activity Logging & Reliability
- `spatie/laravel-activitylog` for auditability
- `spatie/laravel-backup` for backups
- `spatie/laravel-responsecache` + Redis for performance
- `laravel/horizon` for queue monitoring (SMS/email/notification jobs)

### 🧩 Modular Architecture
Clean separation between storefront (`resources/views/front`), admin (`app/Filament`), and domain logic (`app/Models`, `app/Services`), with reusable Blade components and a shared design-system layer.

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
Dynamic pages (About, Contact, …), homepage sliders/banners, admin-managed header/footer menus, homepage sections, a news (blog) module, and an exhibitions/events module — all multilingual.

### 6) Integrations
- SMS: Kavenegar / Melipayamak
- Email: SMTP / SES
- Payments: ZarinPal / IDPay (domestic), receipt upload (international)
- Google reCAPTCHA on public forms
- Excel export (`maatwebsite/excel`) for admin data
- `spatie/laravel-sitemap` + `robots.txt` for SEO

### 7) Storefront Experience
A redesigned public storefront — home, product catalog (grid/list, filters, sort), product detail, categories, cart, checkout, orders, payment, wishlist, news, exhibitions, search, and contact — built around a single design system (shared header/footer/page-header components, modern card and form styling) rather than a generic theme.

### 8) Admin Experience
A custom-branded Filament panel: brand colors and logo wired to site settings, a dark navy sidebar with a branded active-state, a polished dashboard (stats, latest orders, product-status chart, revenue chart), and a language switcher in the topbar — on top of Filament Shield's permission-aware navigation.

---

## 🧱 Domain Model

Implemented entities:

`Language` · `Category` · `Product` · `ProductAttribute` · `ProductAttributeOption` · `ProductAttributeValue` · `Attribute` ·
`Cart` · `CartItem` · `Order` · `OrderItem` · `Payment` · `Coupon` · `Wishlist` · `Review` ·
`Page` · `Post` · `Event` · `Slider` · `Menu` · `MenuItem` · `Setting` · `SeoMeta` · `Redirect` ·
`ContactMessage` · `Newsletter` · `Translation` · `User`

### Translation-ready fields
Translatable fields (JSON-backed) include `name`, `title`, `description`, `excerpt`, `location`, `value`, `slug`, and related copy fields across categories, products, posts, events, pages, sliders, and menu items.

---

## 🏗️ Architecture Principles

- SOLID principles and clean domain modeling
- migration-first development
- admin-resource–driven backend design (Filament Resources/Pages/Widgets)
- centralized multilingual helpers (`LanguageService`, `DatabaseLoader`) for dynamic translation workflows
- a shared front-end design layer (CSS design tokens + reusable Blade components) instead of per-page one-off styling
- maintainable, scalable, modular architecture throughout

---

## ⚙️ Technical Foundation

- ✅ **Laravel 12**
- ✅ **Filament v3** Admin Panel (20 resources, 4 dashboard widgets)
- ✅ **Filament Shield** + **Spatie Permission** (role-based access control)
- ✅ **Spatie Media Library** (images, galleries, video)
- ✅ **Spatie Activitylog**
- ✅ **Spatie Laravel Translatable**
- ✅ **Spatie Laravel Sluggable**
- ✅ **Spatie Laravel Sitemap**
- ✅ **Spatie Laravel Settings** + a custom `Setting` key/value layer for site-wide content
- ✅ **Spatie Laravel Backup**
- ✅ **Spatie Laravel Response Cache**
- ✅ **Spatie Laravel Tags**
- ✅ **Spatie Image Optimizer**
- ✅ **Custom database-backed translation loader** (5 languages, admin-editable, cached)
- ✅ **LanguageService** for active/default locale resolution
- ✅ **shetabit/multipay** for Iranian payment gateways
- ✅ **barryvdh/laravel-dompdf** for order/invoice PDFs
- ✅ **maatwebsite/excel** for admin exports
- ✅ **biscolab/laravel-recaptcha** for form protection
- ✅ **kalnoy/nestedset** for nested category trees
- ✅ **laravel/horizon** for queue monitoring
- ✅ **mcamara/laravel-localization** for locale routing
- ✅ A fully redesigned **storefront UI** (shared header/footer/page-header components, modern product/post/event cards, RTL-first)
- ✅ A custom-branded **admin panel theme** layered on top of Filament's compiled CSS

---

## 🌱 Seeders

- `LanguageSeeder` — Persian, English, Arabic, Hindi, Italian
- `TranslationSeeder` — UI strings for both the `admin` and `messages` groups, in all 5 languages, audited to stay in sync with what's actually used in the views
- `RolePermissionSeeder` — `admin`, `editor`, `sales`, `customer` roles with granular permissions
- `AdminUserSeeder` — a default admin account
- `CategorySeeder` — multilingual parent/child stone-category trees (Igneous, Sedimentary, Metamorphic, Onyx & Alabaster, Travertine, …)
- `AttributeSeeder` — reusable product specification attributes
- `ProductSeeder` — sample catalog data
- `SliderSeeder` — homepage slider content
- `MenuSeeder` — header/footer menu structure
- `EventSeeder` — sample exhibitions

Run all of them via `php artisan db:seed`, or target one with `php artisan db:seed --class=TranslationSeeder`.

---

## 🧰 Technology Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12 |
| Admin Panel | Filament v3 (custom-branded theme) |
| Frontend | Blade + a custom CSS design system (RTL-first) |
| Language | PHP 8.2+ |
| Authorization | Filament Shield + Spatie Permission |
| Media Management | Spatie Media Library + Spatie Image Optimizer |
| Translations | Spatie Laravel Translatable + custom DB-backed UI translation loader |
| Activity Logging | Spatie Activitylog |
| Backups | Spatie Laravel Backup |
| Caching | Spatie Response Cache + Redis |
| SEO | artesaos/seotools + Spatie Sitemap |
| Slugs | Spatie Laravel Sluggable |
| Category Trees | kalnoy/nestedset |
| Queues | Laravel Horizon |
| Locale Routing | mcamara/laravel-localization |
| SMS | Kavenegar / Melipayamak |
| Email | SMTP / SES |
| Payments (IR) | ZarinPal / IDPay via shetabit/multipay |
| Payments (Intl.) | Receipt upload + manual approval |
| PDF | barryvdh/laravel-dompdf |
| Exports | maatwebsite/excel |
| Spam Protection | Google reCAPTCHA |
| Database | MySQL / MariaDB |

---

## 🎨 Frontend Design System

The storefront and admin panel share one visual identity — a refined version of the brand's original orange/navy palette, warmed up with stone-toned neutrals instead of cold grays:

- a single CSS design-system file (`theme-modern.css`) layered on top of the legacy template, so the whole site — home, catalog, categories, cart, checkout, account pages, blog, exhibitions — reads as one cohesive product instead of a patchwork of templated pages
- a search-forward homepage hero, category grid, and product/post/event cards inspired by modern marketplace UX patterns
- a shared page-header component used across every inner page, so breadcrumb/heading styling never has to be rebuilt per page
- the Filament admin panel re-themed via a render-hook CSS layer (no custom Vite build required): a dark navy sidebar with a branded active state, a polished dashboard, and a branded login screen
- RTL handled as a first-class concern throughout, not bolted on afterward

---

## 🗺️ Development Roadmap

### Phase 0 — Environment Setup
- [x] PHP / Composer / Node installation
- [x] Laravel 12 project creation
- [x] PHPStorm configuration
- [x] Git repository + `.gitignore` + initial commit

### Phase I — Foundation
- [x] Laravel installation
- [x] Filament setup
- [x] Shield / permission structure
- [x] Role definitions (Admin / Editor / Sales / Customer)
- [x] Media library integration
- [x] Activity logging integration
- [x] Translation support integration
- [x] Language management infrastructure
- [x] Active/default language caching layer

### Phase II — Core Domain Modeling
- [x] Categories (nested)
- [x] Products
- [x] Dynamic attributes
- [x] Translation-ready schema
- [x] Filament resources for domain entities
- [x] Slug automation
- [x] Dynamic multilingual resource fields
- [x] Multilingual category seed data

### Phase III — Catalog & Frontend
- [x] Homepage slider
- [x] Latest / featured products sections
- [x] Top and bottom menus (admin-managed)
- [x] Product media galleries (image + thumbnail + gallery + video)
- [x] Filters and sorting (category, status, price, search)
- [x] Grid/list catalog views
- [x] Full storefront redesign (home, header/footer, catalog, categories, product detail)
- [ ] SEO metadata audit per page
- [ ] Further search relevance improvements

### Phase IV — Multilingual
- [x] Language enable/disable from admin panel
- [x] Translatable fields: title / description / slug / meta
- [x] RTL/LTR routing and rendering
- [x] Database-backed, admin-editable UI translations (5 languages)

### Phase V — CMS & SEO
- [x] Dynamic pages (About / Contact / …)
- [x] News and exhibitions module
- [ ] SEO: meta title / description / canonical / Open Graph audit
- [ ] `sitemap.xml` + `robots.txt` verification in production

### Phase VI — Commerce Layer
- [x] Cart / checkout / order workflow
- [x] Iranian payment gateway integration
- [x] International payment: receipt upload + admin approval
- [x] Wishlist, reviews, coupons
- [x] Customer interaction management (contact inbox)
- [ ] Transaction logging / reconciliation reports

### Phase VII — Notifications
- [ ] SMS notifications
- [ ] Email automation
- [ ] Queue-based jobs (Horizon is installed; workflows pending)

### Phase VIII — Performance & Deployment
- [ ] Route / view / config caching audit
- [ ] Image thumbnail optimization pass
- [ ] Security audit
- [ ] Deploy (Docker or traditional server)

---

## 📌 Notes

This project is under active development and continues to evolve. The storefront and admin panel are functionally complete for a single-vendor stone catalog; remaining work is concentrated on notifications, deployment hardening, and SEO polish.

The multilingual layer — both content (`spatie/laravel-translatable`) and UI strings (the custom `Translation` model + `DatabaseLoader`) — is designed to stay flexible as new languages are added, without hardcoded locale-specific changes across resource forms or views.

Security-sensitive files (`.env`, credentials) are excluded from version control.

---

## ✍️ Author

Developed and maintained by **NziCode**

---

## 🔒 License

Proprietary — All rights reserved.
