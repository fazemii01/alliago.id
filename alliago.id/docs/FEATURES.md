# Feature Inventory

## Public Website

### Landing page

- Loads active countries
- Shows featured visa products
- Shows one highlighted visa product with detailed relationships
- Shows active testimonials
- Shows active site FAQs

### Visa catalog

- Public visa listing page
- Public visa detail page by slug

### Informational pages

- Detail page
- Process page
- FAQ page
- Refund policy page
- Privacy policy page

### Localization

- Locale switch endpoint for `id` and `en`
- Session-based language storage

## Client Area

### Authentication

- Client login page and submit action
- Client registration page and submit action
- Logout action

### Dashboard

- Authenticated client dashboard route

### Application workflow

- Create application from a visa product slug
- View application detail
- Redirect pending-payment applications to checkout
- Save traveler details and structured metadata
- Generate reference number
- Create initial application status log

### Documents

- Per-document upload endpoint
- Required and optional document states
- Public disk storage under application-specific paths

### Messaging

- Client message submission per application
- Admin messages can be marked as read from client detail page

### Checkout and invoice

- Application checkout page
- Checkout submission route
- Invoice page route

### Profile management

- Edit profile
- Update profile
- Update password

## Admin Panel

### Applications

- List applications
- Edit applications
- Manage related messages through relation manager

### Catalog and content

- Manage countries
- Manage visa products
- Manage site FAQs
- Manage testimonials

### Users and permissions

- Manage users
- Manage roles

### Billing and payments

- Manage invoices
- Manage payment methods

### Dashboard and widgets

- Filament dashboard page
- Account widget
- Filament info widget
- Invoice overview widget

## Integrations

### Payments

- Xendit webhook endpoint
- Payment method configuration model and admin management

## Data Features

### Visa products support

- Requirements
- Documents
- Process steps
- FAQs
- Add-ons
- Promo label
- Pricing fields
- Active and sort controls

### Application support

- Status tracking
- Document review state
- Messaging
- Notes
- Traveler information
- Metadata payload with price breakdown and delivery configuration

## In-App Documentation Surface

The codebase also contains admin documentation views under `resources/views/filament/pages/docs/` for:

- Applications
- Catalog
- FAQ
- Finance
- Roles
- Settings

These are internal documentation UI assets and should be kept aligned with the Markdown docs in `docs/`.
