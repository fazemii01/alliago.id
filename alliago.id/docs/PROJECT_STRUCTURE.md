# Project Structure

## Overview

This repository is a Laravel monolith that serves a marketing website, client application workflows, and an internal admin dashboard. The structure is broadly conventional Laravel with Filament resources layered on top.

## Root Layout

```text
app/
bootstrap/
config/
database/
lang/
node_modules/
public/
references/
resources/
routes/
scripts/
storage/
tests/
vendor/
artisan
composer.json
package.json
README.md
globalpass_visa_platform_development_plan.md
```

## Directory Roles

## `app/`

Main PHP application code.

Important areas:

- `app/Http/Controllers`: public, client, auth, checkout, and webhook controllers
- `app/Models`: Eloquent domain models
- `app/Providers/Filament`: admin panel registration
- `app/Filament/Resources`: Filament CRUD resources, pages, widgets, and relation managers
- `app/Filament/Pages`: custom admin pages, including documentation-related pages

## `bootstrap/`

Laravel framework bootstrap files.

## `config/`

Application configuration files.

## `database/`

Database schema and seed-related assets.

Important area:

- `database/migrations`: core tables for users, permissions, visa catalog, application workflow, payments, and content

## `lang/`

Localization files used with the locale switch route.

## `public/`

Publicly served assets and application entrypoint.

## `references/`

Project reference documents used for internal guidance and planning.

Current files:

- `references/development_workflows.md`
- `references/architecture_patterns.md`
- `references/tech_stack_guide.md`

## `resources/`

Frontend templates and asset sources.

Important areas:

- `resources/views`: Blade templates
- `resources/js`: TypeScript entry and browser behavior
- `resources/css`: styling source

Notable view area:

- `resources/views/filament/pages/docs/`: admin documentation views

## `routes/`

HTTP route definitions. The project currently uses `routes/web.php` for all exposed route areas.

## `scripts/`

Local utility scripts for scaffolding and analysis.

## `storage/`

Application storage for framework runtime data and uploaded files.

## `tests/`

Automated test suite location.

## Important Files

## `README.md`

Repository entrypoint and high-level documentation index.

## `globalpass_visa_platform_development_plan.md`

Long-form development roadmap and product direction reference.

## `composer.json`

Backend dependencies and Laravel development scripts.

## `package.json`

Frontend toolchain and Vite scripts.

## `routes/web.php`

Current route map for public, client, admin, and webhook endpoints.

## `app/Providers/Filament/AdminPanelProvider.php`

Defines the Filament admin panel path, resources, widgets, and access middleware.

## `app/Http/Controllers/ClientApplicationController.php`

Contains the main client application flow for viewing, creating, and submitting visa applications.

## Main Code Areas By Product Surface

## Public website

- Controllers: `LandingPageController`, `VisaCatalogController`, `PageController`
- Views: public Blade pages under `resources/views`
- Content models: `Country`, `VisaProduct`, `SiteFaq`, `Testimonial`

## Client area

- Controllers: `Client*Controller` classes and `ClientAuthController`
- Models: `Application`, `ApplicationDocument`, `ApplicationMessage`, `ApplicationStatusLog`
- Views: client dashboards, forms, checkout, invoice, and profile pages

## Admin area

- Provider: `AdminPanelProvider`
- Resources: `app/Filament/Resources/*`
- Pages: `app/Filament/Pages/*`
- Widgets: `app/Filament/Widgets/*` and resource-specific widgets

## Filament Resource Structure

Each Filament resource typically contains:

- Main resource class
- `Pages/` subdirectory
- Optional `RelationManagers/`
- Optional `Widgets/`

Examples:

- `ApplicationResource`
- `ApplicationResource/Pages`
- `ApplicationResource/RelationManagers`
- `InvoiceResource/Widgets`

## Current Structural Risks

- `routes/web.php` currently mixes all route audiences
- Business workflows are still controller-centric in some places
- `FalseResource/Widgets/DashboardStatsWidget.php` appears structurally inconsistent with the rest of the resource layout

## Suggested Future Structure Direction

As the platform grows, the next internal split should be:

```text
app/
  Domain/
    Visa/
    Application/
    Billing/
    Content/
  Actions/
  Data/
```

That future direction is not required for the current implementation, but it is the clearest path if client and admin workflows continue to expand.
