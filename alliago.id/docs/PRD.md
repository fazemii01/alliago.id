# Product Requirements Document

## Product Name

Alliago.id Visa Platform

## Product Summary

Alliago.id is a visa services platform that combines a public marketing website, a client self-service application area, and an internal operations dashboard. The current product allows admins to manage visa offerings and supporting content while clients can browse visa products, submit applications, upload documents, complete checkout, and track progress.

## Product Goals

- Make visa products easy to publish and manage without editing code
- Let clients submit visa applications through a guided digital workflow
- Centralize document collection and application status tracking
- Provide an internal admin panel for operations, content, finance, and user management
- Keep pricing, requirements, process steps, and policy information visible to users

## Primary User Groups

### Public visitor

- Browses visa information
- Evaluates destinations and visa products
- Reads FAQs, process information, and legal policies

### Client applicant

- Registers or logs in
- Creates an application for a visa product
- Uploads supporting documents
- Selects payment method and add-ons
- Checks invoice and status updates
- Communicates with admins through application messages

### Admin operator

- Manages visa products and countries
- Reviews applications and uploaded files
- Tracks payment configuration and invoice data
- Manages testimonials, FAQs, users, and roles

## Current Product Surfaces

### 1. Public website

Current implemented scope:

- Landing page
- Visa listing page
- Visa detail page
- FAQ page
- Process page
- Detail page
- Refund policy page
- Privacy policy page
- Locale switcher for `id` and `en`

### 2. Client area

Current implemented scope:

- Registration
- Login
- Dashboard
- Application creation
- Application detail page
- Document upload endpoint
- Application messages endpoint
- Checkout page
- Invoice page
- Profile edit
- Password update
- Logout

### 3. Admin panel

Current implemented scope through Filament:

- Application management
- Country management
- Invoice management
- Payment method management
- Role management
- Site FAQ management
- Testimonial management
- User management
- Visa product management

## Core Functional Requirements

## Visa Catalog

The system must allow admins to manage visa products with:

- Country
- Name and slug
- Visa type
- Promo label
- Processing time
- Stay duration
- Validity
- Base price and discount price
- Short and full description
- Active status and sort order

The system must allow each visa product to have:

- Requirements
- Required documents
- Process steps
- FAQs
- Add-ons

The public site must render active visa products dynamically.

## Landing Content

The system must support:

- Featured visa products
- Highlight product section
- Active testimonials
- Active site FAQs
- Active countries for discovery

## Client Authentication

The system must allow a guest to:

- Open a login page
- Open a registration page
- Authenticate into the client area

The system must redirect `/login` to the client login flow.

## Application Submission

The system must allow an authenticated client to create an application for an active visa product.

The application flow currently requires:

- Traveler name
- Traveler email
- Optional traveler phone
- Optional notes
- Optional departure date
- Delivery method
- Optional hard-file handling fields when required
- Payment method
- Optional add-ons
- Processing time type
- Subtotal, tax, and total values

When an application is created, the system must:

- Generate a `GP-` prefixed reference number
- Set status to `pending_payment`
- Save a metadata payload with price breakdown and selected workflow options
- Insert an application status log entry
- Create document records for the selected visa product requirements

## Document Uploads

The system must support file upload per required visa document with:

- PDF or image files
- 5 MB maximum size
- Stored file path on the `public` disk
- Per-document status tracking

## Status And Messaging

The system must support:

- Application status logs
- Client and admin messages per application
- Marking unread admin messages as read when the client opens the application detail page

## Checkout And Invoice

The system must support:

- Checkout page for pending-payment applications
- Checkout submission flow
- Invoice page for the application
- Payment method configuration management
- Payment webhook endpoint for Xendit

## Admin Operations

The admin panel must:

- Be protected by authentication
- Restrict access through the admin-role middleware boundary
- Expose CRUD and operational screens through Filament resources

## Non-Functional Requirements

- Laravel monolith architecture with clear internal boundaries
- Server-rendered UX by default
- Role-based access control for admin operations
- Auditable application status history
- Maintainable documentation of routes, models, and flows
- Clear separation between public, client, and admin concerns

## Current Known Constraints

- Most route definitions still live in a single `routes/web.php` file
- Application creation logic currently combines validation, persistence, metadata composition, and document record creation in one controller action
- The project contains both current implementation docs and broader roadmap references, so docs must distinguish implemented behavior from future direction

## Success Criteria

Documentation is successful when:

- A new developer can understand what the product does without reading all source files
- The public, client, and admin surfaces are clearly separated in docs
- The implemented routes, resources, models, and flows are visible from the documentation set
- Product intent and current implementation are both documented without mixing them carelessly
