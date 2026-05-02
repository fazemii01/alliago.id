# Alliago.id Visa Platform — Development Plan

## 1. Product Goal

Build a Laravel-based visa platform where:

- Visa products are easy to manage.
- Users can apply for visas and upload documents.
- Admins can process applications quickly.
- Admins can manage promos, pricing, add-ons, and timelines.
- Pricing, processing time, requirements, and documents are transparent to users.

```txt
Goal:
Build a Laravel-based visa platform where admins can manage visa products,
users can apply/upload documents, and the company can process applications
transparently with pricing, timelines, promos, and status tracking.
```

---

## 2. Recommended Tech Stack

### Core Stack

```txt
Backend: Laravel
Frontend: Blade + Livewire
Styling: Tailwind CSS
Admin Dashboard: Filament
Database: MySQL
Queue: Redis / Laravel Queue
Storage: Laravel Storage + S3-compatible storage
Payments: Midtrans or Xendit
Permissions: Spatie Laravel Permission
```

### Recommended Final Stack

```txt
Laravel
Blade
Livewire
Alpine.js
Tailwind CSS
Filament
MySQL
Redis Queue
Laravel Storage
Midtrans or Xendit
Spatie Permission
```

### Why This Stack

```txt
Laravel
Main backend and business logic.

Blade + Livewire
Public pages, client dashboard, application forms, uploads.

Tailwind CSS
Website and dashboard design system.

Filament
Admin dashboard and internal operations.

MySQL
Database for products, applications, users, payments.

Redis Queue
Emails, notifications, document processing, and payment callbacks.

Laravel Storage
Private document upload system.

Midtrans / Xendit
Payment gateway for Indonesian market.

Spatie Permission
Admin, staff, and client roles.
```

---

## 3. Main Platform Areas

The platform should be divided into three main areas:

```txt
Public Website
Client Area
Admin Dashboard
```

### Public Website

```txt
Home page
Visa listing page
Visa detail page
FAQ
Testimonials
Promo pages
Blog / guide pages
```

### Client Area

```txt
Register / login
My applications
Upload documents
Track visa status
Payment history
Profile
Notifications
Messages / notes from admin
```

### Admin Dashboard

```txt
Manage visa products
Manage countries
Manage requirements
Manage documents
Manage applications
Review uploaded files
Update application status
Manage payments
Manage add-ons
Manage testimonials
Manage FAQs
Manage promos
Manage admin users
```

---

## 4. Core Modules

## 4.1 Visa Product Management

Admin should be able to manage visa services without editing code.

Example visa products:

```txt
Japan Visa Waiver
Korea Tourist Visa
Australia Visitor Visa
Schengen Tourist Visa
US Tourist Visa
```

Each visa product should include:

```txt
Country
Visa name
Visa type
Short description
Full description
Processing time
Stay duration
Validity period
Base price
Discount price
Promo label
Required documents
Requirements
Process steps
FAQs
Add-ons
Testimonials
Active / inactive status
```

The visa detail page should be generated dynamically from admin data.

---

## 4.2 Pricing, Promo, and Add-ons

Admin should be able to create and manage promos.

Example promos:

```txt
14% OFF Japan Visa Waiver
Promo Ramadhan
Family package discount
Express processing add-on
Document review add-on
Translation add-on
Travel insurance add-on
```

Recommended pricing structure:

```txt
Visa Product
- Base price
- Promo price
- Discount percentage
- Promo start date
- Promo end date
- Add-ons
```

Example add-ons:

```txt
Express Review — IDR 150.000
Document Translation — IDR 250.000
Travel Insurance — IDR 100.000
Form Filling Assistance — IDR 75.000
Courier Service — IDR 50.000
```

---

## 4.3 User Application Flow

The client flow should be simple:

```txt
Choose visa
Create account / login
Fill application form
Upload documents
Choose add-ons
Review total price
Pay
Track status
Receive result
```

Recommended V1 flow:

```txt
1. User clicks "Ajukan Sekarang"
2. User logs in or registers
3. System creates application draft
4. User fills applicant information
5. User uploads required documents
6. User selects add-ons
7. User confirms price
8. User pays
9. Admin reviews application
10. User tracks status from dashboard
```

---

## 4.4 Client Dashboard

Client dashboard should include:

```txt
My Applications
Application Detail
Upload Documents
Payment Status
Status Timeline
Messages / Notes from Admin
Profile
```

Application detail should show:

```txt
Visa name
Destination country
Application status
Missing documents
Uploaded documents
Selected add-ons
Total price
Payment status
Admin notes
Timeline
```

Example status timeline:

```txt
Draft
Waiting for Payment
Paid
Documents Under Review
Documents Need Revision
Submitted
Processing
Approved
Completed
Rejected
Refunded
```

---

## 4.5 Admin Dashboard

Use Filament for the admin dashboard.

Admin should manage:

```txt
Visa Products
Countries
Applications
Customers
Uploaded Documents
Payments
Promos
Add-ons
FAQs
Testimonials
Admin Users
```

Most important admin screens:

```txt
Applications Queue
Application Detail
Document Review
Status Update
Payment Review
Promo Management
Visa Product Editor
```

Admin should be able to:

```txt
View new applications
Filter by status
Check uploaded documents
Mark document as approved / rejected
Add notes for client
Request document revision
Update application status
Upload final visa result
Manage promo prices
Manage visa product content
```

---

## 5. Authentication and Roles

Recommended route structure:

```txt
/client/login
/admin/login
```

Recommended dashboard routes:

```txt
/client/dashboard
/admin
```

Recommended roles:

```txt
client
staff
admin
super_admin
```

Use a single users table with roles and permissions.

Recommended package:

```txt
spatie/laravel-permission
```

---

## 6. Application Status Workflow

Define application statuses early.

Recommended statuses:

```txt
draft
waiting_payment
paid
documents_pending
under_review
documents_need_revision
submitted_to_authority
processing
approved
rejected
completed
cancelled
refunded
```

These statuses will be used in:

```txt
Client dashboard
Admin dashboard
Application timeline
Email notifications
WhatsApp notifications
Payment logic
Operational reporting
```

---

## 7. Database Models

Recommended main Laravel models:

```txt
User
Role
Country
VisaProduct
VisaRequirement
VisaDocument
VisaProcessStep
VisaFaq
VisaAddon
Promo
Application
Applicant
ApplicationDocument
ApplicationAddon
Payment
ApplicationStatusLog
AdminNote
Testimonial
```

---

## 8. Relationship Map

```txt
Country
has many VisaProducts

VisaProduct
has many Requirements
has many RequiredDocuments
has many ProcessSteps
has many FAQs
has many Addons
has many Promos
has many Applications

User
has many Applications

Application
belongs to User
belongs to VisaProduct
has many ApplicationDocuments
has many ApplicationAddons
has many StatusLogs
has one Payment
```

---

## 9. VisaProduct as the Core Object

The platform should be built around this object:

```txt
VisaProduct
```

Because almost everything connects to it:

```txt
Public page
Price
Promo
Documents
Requirements
Process
FAQs
Add-ons
Applications
Payments
```

Example visa product:

```txt
Country: Japan
Product: Japan Visa Waiver
Processing time: 2–5 working days
Validity: 3 years
Stay duration: 15 days
Normal price: IDR 279.000
Promo price: IDR 239.000

Required documents:
- E-passport
- KTP
- KK
- Passport endorsement page

Process:
1. Upload documents
2. Document review
3. Submission
4. Result delivered
```

The frontend should automatically create a clean detail page from this data.

---

## 10. Version 1 Scope

V1 should include:

```txt
Public homepage
Visa listing page
Visa detail page
Client registration/login
Client dashboard
Apply visa flow
Document upload
Admin dashboard
Visa product management
Application management
Status update
Basic promo pricing
Basic add-ons
Manual payment or payment gateway
Email/WhatsApp-ready notifications
```

---

## 11. Save for Version 2

Do not build these in V1 unless absolutely needed:

```txt
Advanced analytics
Multi-language CMS
Automated WhatsApp bot
Embassy appointment automation
Complex coupon engine
Affiliate/referral system
Mobile app
AI document checking
```

---

## 12. Development Phases

Recommended build phases:

```txt
Phase 1: Project setup + auth + roles
Phase 2: Visa product CMS/admin
Phase 3: Public visa pages
Phase 4: Client application flow
Phase 5: Document upload + review
Phase 6: Payment + promo + add-ons
Phase 7: Status tracking + notifications
Phase 8: Polish, security, deployment
```

---

## 13. Recommended First Milestone

The first milestone should focus on making the product manageable from admin.

### Milestone 1

```txt
Laravel project setup
Database setup
Authentication setup
Roles and permissions
Filament admin panel
Country CRUD
Visa Product CRUD
Visa Requirements CRUD
Visa Documents CRUD
Visa Process Steps CRUD
Visa FAQ CRUD
Visa Add-ons CRUD
```

### Goal of Milestone 1

```txt
Admin can create a complete visa product from the dashboard,
and the public website can display it dynamically.
```

---

## 14. Key Product Principle

The first goal is not to build the most advanced technology stack.

The first goal is:

```txt
Make visa products easy to manage.
Make users able to apply and upload documents.
Make admins able to process applications quickly.
Make admins able to manage promos.
Make pricing, timelines, and documents transparent.
```
