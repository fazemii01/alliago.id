# Development Workflows

## Daily Flow

Use a repeatable loop:

1. inspect the current repo state
2. make the smallest correct change
3. run targeted verification
4. review output for regressions
5. document any architectural decision when it changes team conventions

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

The existing Composer scripts already provide a useful baseline.

```bash
composer run dev
composer run test
```

## Recommended Verification Commands

Use the narrowest command set that proves the change.

### PHP / Laravel

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

### Marketing Page Changes

Typical scope:

- Blade composition
- component styling
- small TypeScript interactions

Checklist:

1. verify desktop and mobile layout
2. ensure selectors used in TypeScript still exist
3. build Vite assets
4. confirm no content regressions in shared components

### Client Workflow Changes

Typical scope:

- forms
- application drafts
- upload state
- user dashboard actions

Checklist:

1. validate authorization boundary
2. verify status transitions
3. add feature tests for the main happy path
4. add at least one failure-path test

### Admin Changes

Typical scope:

- Filament resources
- review queues
- operational dashboards

Checklist:

1. verify permissions by role
2. confirm data integrity across edits
3. isolate reusable business logic outside Filament resources
4. test stateful side effects such as notifications or payments

## Branch Hygiene

This repo may be dirty while multiple threads of work happen. Follow these rules:

- do not revert unrelated changes you did not make
- keep your edits scoped
- inspect touched files carefully before editing
- prefer additive work when the user already has unrelated in-flight modifications

## Testing Priorities

When time is limited, prioritize tests in this order:

1. payment and pricing logic
2. application submission and status changes
3. permission boundaries
4. public conversion-critical flows
5. purely presentational sections

## CI Recommendations

Add a GitHub Actions workflow that runs:

```bash
composer install --no-interaction --prefer-dist
php artisan test
npm ci
npm run build
python scripts/code_quality_analyzer.py .
```

Use caching for Composer and npm once the workflow exists.

## Documentation Workflow

Update docs when one of these happens:

- a new route area is introduced
- a new domain boundary is created
- a new external integration lands
- a workflow becomes operationally important

For architecture changes, add a short ADR in `docs/adr/` with:

- context
- decision
- consequences

## Common Smells

- `routes/web.php` growing with mixed public, client, and admin concerns
- one TypeScript file carrying behavior for unrelated pages
- controllers that both validate, persist, compute pricing, and send notifications
- Blade templates that decide business status instead of rendering already-decided view data

## Practical Next Workflow For This Repo

Given the current state, the most sensible sequence is:

1. stabilize the public website structure
2. replace generic docs with project docs
3. introduce route and testing separation
4. scaffold client-area architecture
5. let Filament own admin CRUD
6. move business workflows into named actions and domain classes as the platform surface grows
