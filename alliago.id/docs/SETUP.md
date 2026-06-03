# Setup And Development

## Requirements

- PHP 8.2
- Composer
- Node.js and npm
- A relational database supported by Laravel

## Backend Setup

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
```

If you prefer to use the repository script:

```bash
composer run setup
```

That script installs dependencies, prepares the environment file, generates the app key, runs migrations, installs npm packages, and builds frontend assets.

## Frontend Setup

```bash
npm install
npm run dev
```

## Run The App Locally

Use the combined Laravel workflow:

```bash
composer run dev
```

This starts:

- `php artisan serve`
- `php artisan queue:listen`
- `php artisan pail`
- `npm run dev`

## Testing

Run the existing Composer test script:

```bash
composer run test
```

Or run Laravel tests directly:

```bash
php artisan test
```

## Useful Verification Commands

```bash
php artisan route:list
php artisan about
php artisan test
npm run build
```

## Frontend Build

```bash
npm run build
```

## Project Workflow

Recommended local loop:

1. Inspect current repo state
2. Make the smallest correct change
3. Run targeted verification
4. Review for regressions
5. Update docs when the system surface changes

## Current Workflow Notes

- The project is a server-first Laravel app, not a SPA-first frontend
- Filament owns the admin panel
- The client area is route and controller driven
- Public pages are Blade-rendered with lightweight frontend enhancement

## Documentation Maintenance Rule

Update documentation whenever:

- A new route area is introduced
- A new domain boundary appears
- A new integration is added
- A workflow becomes operationally important
