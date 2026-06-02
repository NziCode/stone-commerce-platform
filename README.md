<div align="center">

# Stone Commerce Platform
### Enterprise Stone Trading & E-Commerce Platform

A modular platform for **stone catalog management, product specification modeling, multilingual business workflows, and integrated commerce**,
built with **Laravel 12**, **Filament**, and a scalable architecture.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-3.x-FACC15?style=for-the-badge&logoColor=black)](https://filamentphp.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Status](https://img.shields.io/badge/Status-Portfolio%20Project-111827?style=for-the-badge)](#)
[![Maintained By](https://img.shields.io/badge/Maintained%20By-NziCode-0F172A?style=for-the-badge)](https://github.com/NziCode)

<br>

> A scalable system for managing stone products, technical specifications, multilingual catalogs, and order workflows.

</div>

---

## ✨ Overview

**stone-commerce-platform** is a modular backend system designed for the **stone trading and e-commerce industry**.

The platform focuses on:

- structured stone product catalogs
- single-item product listings (unique stone blocks)
- technical stone specifications
- multilingual content management
- admin-driven workflows
- Iranian and international payment integrations
- scalable architecture for full commerce operations

Unlike traditional e‑commerce systems, this platform models **unique stone items**, where each product represents a **specific stone block or slab** — not a quantity-based inventory item.

---

## 🎯 Project Goals

The project aims to provide a flexible foundation for digital stone catalogs, administrative workflows, and commerce operations.

Main goals:

- structured product management
- flexible attribute modeling
- multilingual publishing
- maintainable backend architecture
- Iranian and international payment support
- extensibility for CMS and notification layers

Key design principles:

- clarity
- scalability
- maintainability
- modular development
- long-term extensibility

---

## 🛡️ Usage Notice

> This repository is published primarily for **development and portfolio demonstration**.
>
> Unauthorized redistribution or commercial use of this project without permission is not allowed.

---

## 🏛️ Platform Highlights

### Unique Stone Product Modeling
Designed for managing **individual stone items** with structured metadata such as:

- category
- stone type
- finish
- grade
- thickness
- dimensions
- location / origin
- descriptive technical notes

### 🌍 Multilingual Content System
Built with multilingual support:

- Persian
- English
- Arabic
- Hindi
- Italian
- RTL compatibility
- translatable model fields
- scalable language expansion
- admin-managed active languages
- dynamic translation form generation
- default language-aware display and slug generation

The multilingual system is powered by a dedicated language management layer, allowing the admin panel to dynamically generate translation inputs based on active languages in the system.

### 🔐 Access Control
Administrative access uses a role‑based permission system with defined roles:

- Admin
- Editor
- Sales
- Customer
- granular permissions
- Filament Shield integration

### 🛒 Commerce & Order Workflows
The platform supports both domestic and international buyers:

- Iranian payment gateways (ZarinPal / IDPay / …)
- International orders: receipt / wire transfer upload + manual admin approval
- Order lifecycle management
- SMS and email notifications

### 🧾 Activity Logging
System activities can be tracked for transparency and debugging purposes using structured logging.

### 🧩 Modular Architecture
The codebase is structured to support:

- clean separation of concerns
- reusable components
- maintainable domain models
- scalable system growth

---

## 🚀 Core Business Modules

### 1) Product Information Management
A structured catalog system for stone listings:

- categories
- products
- multilingual descriptions
- SEO-friendly slugs
- technical metadata
- featured items
- media attachments (image + thumbnail + gallery + video)

### 2) Dynamic Attribute Engine
A flexible system for product specifications without rigid database schemas:

- reusable attributes
- configurable options
- product-specific values
- filterable metadata

### 3) Single-Item Product Logic
Each product represents a unique item:

- no traditional inventory counts
- availability controlled by a single `is_available` boolean
- lifecycle management for each listing

### 4) Dynamic Language Management
A dedicated module for managing available locales in the system:

- enable / disable languages from admin panel
- set default language
- RTL / LTR support
- cached active languages for better performance
- dynamic form field generation based on active locales

### 5) CMS Layer
Content management capabilities:

- dynamic pages (About, Contact, …)
- banners and sliders
- top and bottom menus (admin-managed)
- homepage sections
- news and exhibition listings
- multilingual content publishing

### 6) Integrations

- SMS notifications (Kavenegar / Melipayamak / …)
- email workflows (SMTP / SES)
- Iranian payment gateways
- international payment via receipt upload
- background jobs and queue-based processing

---

## 🧱 Current Domain Model

Core entities currently implemented:

- `Language`
- `Category`
- `Product`
- `ProductAttribute`
- `ProductAttributeOption`
- `ProductAttributeValue`

Planned entities:

- `Page`
- `Post` / `News`
- `Order`
- `PaymentProof`
- `Menu`
- `Slider`

### Translation-ready fields

The following fields support JSON-based translations:

- `name`
- `description`
- `location`
- `value`

---

## 🏗️ Architecture Principles

This project follows modern Laravel development practices:

- SOLID principles
- clean domain modeling
- migration-first development
- admin-resource driven backend design
- maintainable and scalable architecture
- centralized multilingual helpers for dynamic translation workflows

---

## ⚙️ Current Technical Foundation

The administrative infrastructure includes:

- ✅ **Laravel 12**
- ✅ **Filament Admin Panel**
- ✅ **Filament Shield**
- ✅ **Spatie Laravel Permission**
- ✅ **Spatie Media Library**
- ✅ **Spatie Activitylog**
- ✅ **Spatie Laravel Translatable**
- ✅ **Spatie Laravel Sluggable**
- ✅ **Spatie Laravel Sitemap**

Additional internal infrastructure includes:

- ✅ **Language management module**
- ✅ **LanguageHelper for active/default locale resolution**
- ✅ **TranslatableFields helper for dynamic Filament form generation**
- ✅ **Dynamic multilingual resource forms**
- ✅ **Default-locale aware table rendering and searching**

---

## 🌱 Seeders

The project includes initial seeders for multilingual setup and sample catalog structure:

- `LanguageSeeder`
    - seeds the default system languages:
        - Persian (`fa`)
        - English (`en`)
        - Arabic (`ar`)
        - Hindi (`hi`)
        - Italian (`it`)

- `CategorySeeder`
    - seeds multilingual parent and child categories for stone classification
    - includes sample category trees such as:
        - Igneous Rocks
        - Sedimentary Rocks
        - Metamorphic Rocks
        - Onyx & Alabaster
        - Traonyx

These seeders provide a ready-to-use multilingual base for development and testing.

---

## 🧰 Technology Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12 |
| Admin Panel | Filament v3 |
| Language | PHP 8.2+ |
| Authorization | Filament Shield + Spatie Permission |
| Media Management | Spatie Media Library |
| Translations | Spatie Laravel Translatable |
| Activity Logging | Spatie Activitylog |
| SEO | artesaos/seotools |
| Sitemap | Spatie Laravel Sitemap |
| Slugs | Spatie Laravel Sluggable |
| Cache / Performance | Redis + Laravel Cache |
| SMS | Kavenegar / Melipayamak |
| Email | SMTP / SES |
| Payments (IR) | ZarinPal / IDPay |
| Payments (International) | Receipt upload + manual approval |
| Database | MySQL / MariaDB |

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
- [x] Categories
- [x] Products
- [x] Dynamic attributes
- [x] Translation-ready schema
- [x] Filament resources for domain entities
- [x] Slug automation
- [x] Dynamic multilingual resource fields
- [x] Multilingual category seed data

### Phase III — Catalog & Frontend
- [ ] Homepage slider
- [ ] Latest products section
- [ ] Top and bottom menus (admin-managed)
- [ ] Product media galleries (image + thumbnail + gallery + video)
- [ ] Advanced filters
- [ ] SEO metadata
- [ ] Search improvements

### Phase IV — Multilingual
- [ ] Language enable/disable from admin panel
- [ ] Translatable fields: title / description / slug / meta
- [ ] RTL/LTR routing and rendering

### Phase V — CMS & SEO
- [ ] Dynamic pages (About / Contact / …)
- [ ] SEO: meta title / description / canonical / Open Graph
- [ ] `sitemap.xml` + `robots.txt`
- [ ] News and exhibitions module

### Phase VI — Commerce Layer
- [ ] Inquiry / order workflows
- [ ] Iranian payment gateway integration
- [ ] International payment: receipt upload + admin approval
- [ ] Customer interaction management
- [ ] Transaction logging

### Phase VII — Notifications
- [ ] SMS notifications
- [ ] Email automation
- [ ] Queue-based jobs

### Phase VIII — Performance & Deployment
- [ ] Route / view / config caching
- [ ] Image thumbnail optimization
- [ ] Security audit
- [ ] Deploy (Docker or traditional server)

---

## 📌 Notes

This project is under active development and may evolve as requirements expand.

The multilingual layer has been designed to remain flexible as new languages are added in future phases, without requiring hardcoded locale-specific changes across resource forms.

Security-sensitive files (`.env`, credentials) are excluded from version control.

---

## ✍️ Author

Developed and maintained by **NziCode**

---

## 🔒 License

Proprietary — All rights reserved.
