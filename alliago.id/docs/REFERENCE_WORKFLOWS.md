# Reference Workflows

This file is a Markdown documentation copy of the project workflow guidance currently stored in `references/development_workflows.md`.

## Daily Flow

Use a repeatable loop:

1. Inspect the current repo state
2. Make the smallest correct change
3. Run targeted verification
4. Review output for regressions
5. Document any architectural decision when it changes team conventions

## Local Setup

### Backend

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### Frontend

```bash
npm install
npm run dev
```

### Combined Laravel Workflow

```bash
composer run dev
composer run test
```

## Recommended Verification Commands

### PHP and Laravel

```bash
php artisan test
php artisan route:list
php artisan about
```

### Frontend

```bash
npm run build
```

### Internal Toolkit

```bash
python scripts/project_scaffolder.py .
python scripts/code_quality_analyzer.py .
python scripts/fullstack_scaffolder.py .
```

## Workflow By Change Type

### Marketing page changes

Checklist:

1. Verify desktop and mobile layout
2. Ensure selectors used in TypeScript still exist
3. Build Vite assets
4. Confirm no content regressions in shared components

### Client workflow changes

Checklist:

1. Validate authorization boundary
2. Verify status transitions
3. Add feature tests for the main happy path
4. Add at least one failure-path test

### Admin changes

Checklist:

1. Verify permissions by role
2. Confirm data integrity across edits
3. Isolate reusable business logic outside Filament resources
4. Test stateful side effects such as notifications or payments

## Branch Hygiene

- Do not revert unrelated changes you did not make
- Keep edits scoped
- Inspect touched files carefully before editing
- Prefer additive work when unrelated in-flight modifications exist

## Testing Priorities

1. Payment and pricing logic
2. Application submission and status changes
3. Permission boundaries
4. Public conversion-critical flows
5. Purely presentational sections

## CI Recommendation

```bash
composer install --no-interaction --prefer-dist
php artisan test
npm ci
npm run build
python scripts/code_quality_analyzer.py .
```

## Documentation Workflow

Update docs when:

- A new route area is introduced
- A new domain boundary is created
- A new external integration lands
- A workflow becomes operationally important

For architecture changes, add a short ADR in `docs/adr/` with:

- Context
- Decision
- Consequences

## Common Smells

- `routes/web.php` growing with mixed public, client, and admin concerns
- One TypeScript file serving unrelated pages
- Controllers that validate, persist, compute pricing, and send notifications all at once
- Blade templates deciding business status instead of rendering resolved view data

## Practical Next Workflow For This Repo

1. Stabilize the public website structure
2. Replace generic docs with project docs
3. Introduce route and testing separation
4. Scaffold client-area architecture
5. Let Filament own admin CRUD
6. Move business workflows into named actions and domain classes as the platform surface grows
