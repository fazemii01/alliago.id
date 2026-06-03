# Architecture

## Architectural Posture

Alliago.id is currently a Laravel monolith with three visible product surfaces inside one deployable application:

- Public website
- Client application area
- Admin dashboard

This is the correct level of complexity for the current state of the project. The app uses one backend codebase, one primary relational schema, one asset pipeline, and one admin interface.

## Technology Stack

- Laravel 12
- PHP 8.2
- Blade views
- Vite
- Tailwind CSS v4
- TypeScript for lightweight browser behavior
- Filament 3.3 for admin tooling
- Spatie Laravel Permission for access control

## System Surfaces

## 1. Public Website

Responsibilities:

- Landing page
- Visa listing
- Visa detail pages
- Informational pages
- FAQ and policy content
- Country and product discovery

Entry points:

- `/`
- `/visa`
- `/visa/{slug}`
- `/detail`
- `/proses`
- `/faq`
- `/refund-policy`
- `/privacy-policy`

Current controller shape:

- `LandingPageController`
- `VisaCatalogController`
- `PageController`

`LandingPageController` currently composes the landing page by loading active countries, featured products, a highlighted visa product with detail relationships, active testimonials, and active site FAQs.

## 2. Client Area

Responsibilities:

- Registration and login
- Application creation
- Document collection
- Checkout
- Invoice access
- Messaging and status tracking
- Profile management

Entry points:

- `/client/login`
- `/client/register`
- `/client/dashboard`
- `/client/applications/*`
- `/client/profile`
- `/client/password`

Current controllers:

- `Auth/ClientAuthController`
- `ClientDashboardController`
- `ClientApplicationController`
- `ClientDocumentController`
- `ClientMessageController`
- `ClientCheckoutController`
- `ClientInvoiceController`
- `ClientProfileController`

Important current behavior:

- Application ownership is enforced in `ClientApplicationController@show`
- Pending-payment applications are redirected to checkout before normal detail rendering
- Admin messages are marked as read when the client opens the application page
- Application creation stores structured metadata including delivery, payment, add-ons, processing type, and pricing information

## 3. Admin Dashboard

Responsibilities:

- CRUD for operational data
- Application review
- Payment method management
- User and role management
- Content management

The admin panel is provided by `app/Providers/Filament/AdminPanelProvider.php` with:

- Panel id `admin`
- Path `/admin`
- Filament login enabled
- Amber primary color
- Custom auth middleware `EnsurePanelUserHasAdminRole`

Current resources:

- `ApplicationResource`
- `CountryResource`
- `InvoiceResource`
- `PaymentMethodResource`
- `RoleResource`
- `SiteFaqResource`
- `TestimonialResource`
- `UserResource`
- `VisaProductResource`

Notable resource internals:

- `ApplicationResource` includes a `MessagesRelationManager`
- `InvoiceResource` includes `InvoiceOverview`

## Domain Model

Core models currently implemented:

- `Application`
- `ApplicationDocument`
- `ApplicationMessage`
- `ApplicationStatusLog`
- `Country`
- `PaymentMethod`
- `SiteFaq`
- `Testimonial`
- `User`
- `VisaAddon`
- `VisaDocument`
- `VisaFaq`
- `VisaProcessStep`
- `VisaProduct`
- `VisaRequirement`

These models form four practical domains:

- Visa catalog
- Application workflow
- Payments and invoices
- Marketing and content

## Data Relationships

### Visa catalog

- A country has many visa products
- A visa product has many requirements
- A visa product has many documents
- A visa product has many process steps
- A visa product has many FAQs
- A visa product has many add-ons

### Application workflow

- A user has many applications
- An application belongs to a visa product
- An application has many application documents
- An application has many status logs
- An application has many messages

### Content

- Testimonials and site FAQs are standalone content tables used by the public site

## Route Architecture

All routes are currently defined in `routes/web.php`.

This file currently mixes:

- Public routes
- Client auth and client protected routes
- Admin dashboard route
- Payment webhook route

This is workable now, but the next architectural improvement should be to split route files by audience:

- `routes/web.php` for public
- `routes/client.php` for authenticated client flows
- `routes/admin.php` for non-Filament admin endpoints

## Application Creation Workflow

Current orchestration lives in `ClientApplicationController@store`:

1. Validate request input
2. Create application record with status `pending_payment`
3. Save metadata payload
4. Insert initial status log
5. Create one `ApplicationDocument` per visa document definition
6. Persist uploaded files where available
7. Redirect to checkout

This is a clear candidate for future extraction into a dedicated action such as `SubmitVisaApplication` once the workflow grows.

## Admin Authorization Boundary

Filament panel access is protected by:

- Filament `Authenticate`
- Custom `EnsurePanelUserHasAdminRole`

This means admin authorization is currently enforced at panel entry instead of relying only on conditional UI visibility.

## Payments Boundary

The application includes:

- Payment methods table and admin resource
- Checkout controller flow
- Invoice page flow
- Xendit webhook endpoint at `/webhooks/xendit`

This indicates payments are integrated as a platform concern, even if the full reconciliation model may still evolve.

## Documentation Boundary

There are two kinds of documentation in the repository:

- Current implementation documentation under `docs/`
- Planning/reference documents that describe recommended workflows and broader direction

The new docs set is intended to describe what exists now while still acknowledging the broader platform roadmap.

## Structural Observations

- The codebase is coherent as a monolith
- Public, client, and admin concerns are visible but not fully separated in routing yet
- Filament is correctly positioned as the back-office surface
- Application workflow logic is implemented and usable, but some controller actions already mix orchestration concerns that may later move into named actions or domain services
- `app/Filament/Resources/FalseResource/Widgets/DashboardStatsWidget.php` appears unusually placed and should be reviewed when cleaning up admin structure
