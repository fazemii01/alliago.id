# Tech Stack Guide

## Current Baseline

This repository is a Laravel 12 application with a Blade-driven frontend, Vite asset pipeline, TypeScript for lightweight browser behavior, and Tailwind CSS v4 with custom design tokens. The current public site is a marketing homepage composed from Blade components and enhanced with AOS-based animation and small DOM interactions.

The broader product direction points toward a visa assistance platform with three surfaces:

- Public website
- Client area
- Admin dashboard

The stack should be treated as server-first Laravel rather than a SPA-first frontend.

## Backend

### Laravel

Use Laravel conventions first. Add abstractions only when the domain earns them.

- Keep route declarations thin.
- Move non-trivial route closures into controllers.
- Push business rules into domain-oriented classes once workflows become multi-step.
- Prefer explicit form requests, policies, jobs, and notifications over ad hoc controller logic.

### Filament

Filament is already present in Composer dependencies and is the natural fit for the admin dashboard.

- Use Filament Resources for CRUD-heavy back-office areas such as visa products, promos, FAQs, and testimonials.
- Use custom pages or widgets for dashboards and approval queues.
- Keep admin-only logic isolated from public controllers and views.

### Spatie Permission

Permission management should define operational boundaries early.

- Model roles around real operators: super admin, operations, finance, content, support.
- Authorize at policy and panel-entry points, not only in UI visibility.
- Avoid hardcoded role strings inside many controllers. Centralize checks where possible.

## Frontend

### Blade First

The current UI is componentized through Blade components under `resources/views/components/home`.

- Keep page composition in route-level views.
- Keep reusable chunks in components.
- Avoid turning Blade components into hidden application controllers.

Suggested split:

- `resources/views/pages/*` for route-level pages
- `resources/views/components/*` for reusable presentational pieces
- `resources/views/livewire/*` for interactive server-driven components when needed

### TypeScript

The current TypeScript entrypoint is intentionally small and DOM-driven.

- Reserve `resources/js/app.ts` for bootstrapping.
- Split feature scripts when a single file starts serving multiple page concerns.
- Prefer `data-*` hooks over fragile selector coupling.
- Remove `console.log` and temporary instrumentation before merging.

### Tailwind CSS v4

The CSS layer already uses Tailwind v4 and custom theme tokens.

- Keep shared design tokens in one place.
- Prefer component classes for repeated marketing patterns.
- Avoid inline utility duplication when the same visual pattern appears across sections.
- Treat animation and blur effects carefully on mobile.

## Data And Domain Direction

The product plan implies several domain areas that should stay explicit:

- Visa catalog and country-specific requirements
- Pricing, promos, and add-ons
- Applicant profiles and application drafts
- Document uploads and validation
- Payments and reconciliation
- Status tracking and operator actions

As those flows appear, prefer a structure like this:

```text
app/
  Domain/
    Visa/
    Application/
    Billing/
    Content/
  Actions/
  Data/
  Support/
```

This keeps domain language visible and prevents everything from collapsing into controllers or Livewire components.

## Persistence And Infrastructure

The product plan mentions MySQL and Redis-oriented workloads even though the repo still looks early-stage.

- Use relational tables for core entities and workflow state.
- Use queues for notifications, file processing, and heavier integrations.
- Keep storage concerns explicit: originals, generated previews, and internal documents should not share accidental conventions.

## Anti-Patterns To Avoid

- Leaving the default Laravel README in place once the product becomes team-facing.
- Growing `routes/web.php` into a single file for every public, client, and admin route.
- Encoding business rules directly in Blade templates.
- Mixing Filament-only admin concerns into public website controllers.
- Treating TypeScript as a dumping ground for page-specific behavior.

## Recommended Next Steps

1. Move beyond closure routes for anything more complex than simple static pages.
2. Establish separate route files for public, client, and admin flows.
3. Add real feature tests around the public site and the first business workflows.
4. Start recording architecture decisions in `docs/adr/` once the client and admin areas begin.
