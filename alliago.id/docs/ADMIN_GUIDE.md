# Admin Guide

## Admin Surface

The internal admin panel is built with Filament and mounted at `/admin`.

Additional admin-facing route:

- `/admin-dashboard`

## Access Control

Admin access is protected by:

- Filament authentication middleware
- Custom middleware `EnsurePanelUserHasAdminRole`

This means a signed-in user must also satisfy the admin-role requirement to enter the panel.

## Admin Panel Configuration

Defined in `app/Providers/Filament/AdminPanelProvider.php`.

Current configuration includes:

- Panel id: `admin`
- Path: `admin`
- Filament login enabled
- Dark mode disabled
- Amber primary color
- Discovered resources, pages, and widgets from the `app/Filament` namespace

## Main Admin Resources

### `ApplicationResource`

Purpose:

- Review and manage client applications
- Access related client-admin message threads

Current pages:

- `ListApplications`
- `EditApplication`

Relation managers:

- `MessagesRelationManager`

### `CountryResource`

Purpose:

- Manage countries that organize visa products

Pages:

- `ListCountries`
- `CreateCountry`
- `EditCountry`

### `VisaProductResource`

Purpose:

- Manage visa catalog products shown on public and client surfaces

Pages:

- `ListVisaProducts`
- `CreateVisaProduct`
- `EditVisaProduct`

### `PaymentMethodResource`

Purpose:

- Manage active payment methods available to the client checkout flow

Pages:

- `ListPaymentMethods`
- `CreatePaymentMethod`
- `EditPaymentMethod`

### `InvoiceResource`

Purpose:

- Manage invoice records and financial monitoring views

Pages:

- `ManageInvoices`

Widgets:

- `InvoiceOverview`

### `SiteFaqResource`

Purpose:

- Manage site-wide FAQ content for the public site

Pages:

- `ListSiteFaqs`
- `CreateSiteFaq`
- `EditSiteFaq`

### `TestimonialResource`

Purpose:

- Manage public testimonials displayed on landing content

Pages:

- `ListTestimonials`
- `CreateTestimonial`
- `EditTestimonial`

### `UserResource`

Purpose:

- Manage application users and internal accounts

Pages:

- `ListUsers`
- `CreateUser`
- `EditUser`

### `RoleResource`

Purpose:

- Manage system roles used with Spatie Permission

Pages:

- `ListRoles`
- `CreateRole`
- `EditRole`

## Admin Responsibilities By Function

## Operations

- Review visa applications
- Track document submission status
- Monitor application progress
- Communicate with clients

## Catalog management

- Add and edit countries
- Add and edit visa products
- Maintain product requirements, documents, process steps, FAQs, and add-ons

## Finance

- Manage payment methods
- Review invoice information
- Respond to payment webhook-driven changes when integrated workflows expand

## Content

- Maintain site FAQs
- Maintain testimonials

## Access and governance

- Maintain users and roles

## Internal Documentation Views

The codebase contains admin docs view files under:

- `resources/views/filament/pages/docs/applications.blade.php`
- `resources/views/filament/pages/docs/catalog.blade.php`
- `resources/views/filament/pages/docs/faq.blade.php`
- `resources/views/filament/pages/docs/finance.blade.php`
- `resources/views/filament/pages/docs/roles.blade.php`
- `resources/views/filament/pages/docs/settings.blade.php`

There is also a custom page class:

- `app/Filament/Pages/DocumentationPage.php`

This indicates the admin panel already has an internal documentation surface that should stay aligned with the Markdown docs.

## Current Admin Cleanup Note

`app/Filament/Resources/FalseResource/Widgets/DashboardStatsWidget.php` appears structurally inconsistent and should be reviewed in a future cleanup pass.
