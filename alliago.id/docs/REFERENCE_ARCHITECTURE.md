# Reference Architecture Patterns

This file is a Markdown documentation copy of the architectural guidance currently stored in `references/architecture_patterns.md`.

## Architectural Posture

Build the product as a Laravel monolith with clear internal boundaries:

- One deployable app
- One primary database
- Separate domain slices inside the codebase
- Server-rendered UX by default
- Targeted interactivity only where it materially improves workflows

## Surface Areas

### Public website

Responsibilities:

- Landing pages
- Visa listing and detail pages
- Promo and guide content
- Lead capture and onboarding entry points

Patterns:

- Blade pages compose reusable components
- Controllers fetch content and simple view models
- TypeScript enhances behavior such as tabs, toggles, and menus

### Client area

Responsibilities:

- Account entry
- Application drafts
- Upload progress
- Payment status
- Timeline and notifications

Patterns:

- Prefer Livewire for form-heavy stateful workflows when needed
- Keep validation and state transitions outside render methods
- Use policies and middleware consistently

### Admin dashboard

Responsibilities:

- Visa product management
- Pricing and promos
- Application review and status changes
- Content administration
- Support operations

Patterns:

- Use Filament resources for CRUD
- Use services or actions for approvals, verification, and side effects
- Keep workflows auditable

## Suggested Internal Layout

```text
app/
  Http/
    Controllers/
  Domain/
    Visa/
    Application/
    Billing/
    Content/
  Actions/
  Data/
  Support/
  Livewire/
resources/
  views/
    pages/
    components/
    livewire/
routes/
  web.php
  client.php
  admin.php
tests/
  Feature/
    Public/
    Client/
    Admin/
  Unit/
    Domain/
```

## Pattern Selection Heuristics

### Controllers

Use when:

- One HTTP action maps cleanly to one request
- The page is mostly read-oriented
- Orchestration is small

### Actions

Use when:

- A workflow changes multiple models
- Validation and business rules span multiple controllers
- The use case might be triggered from multiple interfaces

Examples:

- `SubmitVisaApplication`
- `ApplyPromotionToCart`
- `VerifyPaymentAndAdvanceStatus`

### Domain classes

Use when:

- Rules need explicit business names
- Logic is reused across interfaces
- State transitions must remain explicit

### DTO-style data objects

Use when:

- Payloads need shaping before reaching domain logic
- Multiple interfaces produce the same logical input
- Primitive arrays are making signatures unclear

## Routing Pattern

Prefer audience-based route files:

- `routes/web.php` for public site only
- `routes/client.php` for authenticated applicant area
- `routes/admin.php` for admin flows outside Filament

## Testing Pattern

Layer tests by intent:

- Feature tests for public pages, auth gates, application endpoints, and admin actions
- Unit tests for pricing logic, state transitions, and validation helpers
- Filament or Livewire tests where panel behavior matters

Target business-risk-heavy paths first:

- Pricing and promos
- Upload requirements
- Payment confirmation
- Status transitions
- Permission boundaries

## Anti-Patterns

- Fat Blade templates embedding business decisions
- Large stateful components that mix persistence, validation, side effects, and presentation
- Filament resources containing core business rules needed outside admin
- Generic service names such as `HelperService`

## Migration Path From Current State

1. Keep the marketing site stable
2. Introduce route separation
3. Add domain and action boundaries for visa workflows
4. Add richer stateful interactions only where justified
5. Let Filament own back-office CRUD and operator tooling
