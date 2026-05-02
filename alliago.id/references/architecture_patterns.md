# Architecture Patterns

## Architectural Posture

Build this product as a Laravel monolith with clear internal boundaries.

That means:

- one deployable app
- one primary database
- separate domain slices inside the codebase
- server-rendered UX by default
- targeted interactivity where it materially improves workflows

This is the right complexity level for the current stage.

## Surface Areas

### 1. Public Website

Responsibilities:

- Landing pages
- Visa listing and detail pages
- Promo and guide content
- Lead capture and early onboarding entry points

Patterns:

- Blade pages compose reusable presentational components.
- Controllers or route handlers fetch content and simple view models.
- TypeScript only enhances behavior such as toggles, tabs, menus, and progressive disclosure.

### 2. Client Area

Responsibilities:

- Account entry
- Application drafts
- Upload progress
- Payment status
- Timeline and notifications

Patterns:

- Prefer Livewire for form-heavy, stateful workflows.
- Keep validation and state transitions in actions or domain services, not inside component render methods.
- Use policies and middleware consistently.

### 3. Admin Dashboard

Responsibilities:

- Visa product management
- Pricing and promos
- Application review and status changes
- Content administration
- Support operations

Patterns:

- Use Filament Resources for CRUD.
- Use dedicated services/actions for approvals, payment verification, and side effects.
- Keep admin workflows auditable.

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

## Pattern Selection

### Controllers

Use controllers when:

- the request maps cleanly to one HTTP action
- the page is mostly read-oriented
- orchestration is small

Keep them thin.

### Actions

Use action classes when:

- a workflow changes multiple models
- validation and business rules span more than one controller
- the same use case may be triggered from public, client, admin, queue, or console flows

Examples:

- `SubmitVisaApplication`
- `ApplyPromotionToCart`
- `VerifyPaymentAndAdvanceStatus`

### Domain Classes

Use domain classes when:

- rules need names the business can understand
- logic is reused across interfaces
- state transitions need to remain explicit

Examples:

- eligibility checks
- application completeness checks
- pricing composition rules

### DTO-Style Data Objects

Use `app/Data` objects when:

- request payloads need shaping before entering domain logic
- multiple interfaces produce the same logical input
- primitive arrays are making service signatures unclear

## Routing Pattern

Keep route files separated by audience.

- `routes/web.php`: public site only
- `routes/client.php`: authenticated applicant area
- `routes/admin.php`: admin-only flows when they are not already handled entirely inside Filament

Avoid route-file sprawl until there is real pressure, but do not let `web.php` become the application map for everything.

## Testing Pattern

Use a layered approach.

- Feature tests for public pages, auth gates, application flow endpoints, and admin actions
- Unit tests for pricing logic, state transitions, and validation helpers
- Filament or Livewire tests where panel behavior matters

Target business-risk-heavy paths first:

- pricing and promos
- upload requirements
- payment confirmation
- status transitions
- permission boundaries

## Decision Heuristics

Choose the smallest pattern that keeps the code obvious.

- One page, one query, one view: controller is enough.
- One multi-step workflow with side effects: action.
- One reusable business rule with domain meaning: domain class.
- One repeated payload shape across boundaries: data object.

## Anti-Patterns

- Fat Blade templates that embed business decisions.
- Large Livewire components that mix validation, persistence, side effects, and presentation.
- Filament resources containing core business rules that should also work outside admin.
- Service classes named generically like `HelperService` or `GeneralManager`.

## Migration Path From Current State

The repository currently looks like a polished marketing site inside a Laravel app. As product features land:

1. keep the marketing site stable
2. introduce route separation
3. add domain/action boundaries for visa workflows
4. layer in Livewire for client-stateful interactions
5. let Filament own back-office CRUD and operator tooling

This yields a coherent fullstack Laravel platform without prematurely fragmenting the stack.
