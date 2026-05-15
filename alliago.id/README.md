# Alliago.id

Alliago.id is a Laravel-based visa services platform for managing visa products, collecting client applications, reviewing documents, handling payments, and operating an internal admin panel.

## Project Overview

The application currently contains three main product surfaces:

- Public website for marketing pages, visa discovery, and policy content
- Client area for registration, login, application submission, document upload, checkout, invoices, and profile management
- Admin panel built with Filament for internal operations and content management

## Current Stack

- Backend: Laravel 12
- PHP: 8.2
- Admin panel: Filament 3.3
- Permissions: Spatie Laravel Permission
- Frontend: Blade, Vite, Tailwind CSS v4, TypeScript
- Client-side enhancement: AOS and small DOM interactions
- Database: relational schema managed through Laravel migrations

## Main Capabilities

- Visa catalog with country-specific visa products
- Product requirements, required documents, process steps, FAQs, and add-ons
- Client registration and login flow
- Visa application submission with price breakdown metadata
- Document upload workflow per application
- Application messaging between client and admin
- Checkout and invoice pages
- Payment method configuration and Xendit webhook endpoint
- Filament resources for applications, visa products, countries, FAQs, testimonials, payment methods, users, roles, and invoices

## Route Areas

### Public

- `/`
- `/visa`
- `/visa/{slug}`
- `/detail`
- `/proses`
- `/faq`
- `/refund-policy`
- `/privacy-policy`
- `/lang/{locale}`

### Client

- `/client/login`
- `/client/register`
- `/client/dashboard`
- `/client/applications/*`
- `/client/profile`
- `/client/password`
- `/client/logout`

### Admin

- `/admin`
- `/admin-dashboard`

### Integrations

- `/webhooks/xendit`

## Documentation Index

- `docs/PRD.md`: product requirements based on the current implementation and near-term platform scope
- `docs/ARCHITECTURE.md`: system architecture, app surfaces, domain model, and technical boundaries
- `docs/PROJECT_STRUCTURE.md`: repo layout and the role of each major directory
- `docs/SETUP.md`: local setup, development commands, and verification workflow
- `docs/FEATURES.md`: feature inventory by product area
- `docs/CLIENT_FLOW.md`: end-to-end client journey and application lifecycle
- `docs/ADMIN_GUIDE.md`: admin panel scope and operating model
- `docs/DATA_MODEL.md`: current database entities and relationships
- `docs/REFERENCE_WORKFLOWS.md`: Markdown reference converted from `references/development_workflows.md`
- `docs/REFERENCE_ARCHITECTURE.md`: Markdown reference converted from `references/architecture_patterns.md`

## Local Setup

### Backend

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
```

### Frontend

```bash
npm install
npm run dev
```

### Combined Workflow

```bash
composer run dev
composer run test
```

## Useful Commands

```bash
php artisan route:list
php artisan about
php artisan test
npm run build
```

## Notes On Current State

- The public site and visa platform are implemented in the same Laravel monolith.
- The project still contains planning and reference material that documents the intended long-term platform direction.
- The documentation in this repository is now project-specific and should be updated whenever new route areas, integrations, or workflow-critical features are added.
