# Data Model

## Overview

The current schema supports four practical domains:

- Users and permissions
- Visa catalog
- Application workflow
- Content and payments

## Users And Permissions

### Users

Base user records are created through Laravel user migrations, with additional profile-related fields added later.

### Permissions

Spatie Permission tables are created through:

- `2026_05_01_000100_create_permission_tables.php`

These support role-based admin control.

## Visa Catalog Domain

Defined primarily in:

- `2026_05_01_000300_create_visa_catalog_tables.php`

### `countries`

Fields:

- `name`
- `slug`
- `code`
- `flag_emoji`
- `is_active`

Purpose:

- Organizes visa products by destination country

### `visa_products`

Fields:

- `country_id`
- `name`
- `slug`
- `type`
- `promo_label`
- `processing_time`
- `stay_duration`
- `validity`
- `base_price`
- `discount_price`
- `short_description`
- `description`
- `is_active`
- `sort_order`

Purpose:

- Main sellable visa product entity used in public and client flows

### `visa_requirements`

Fields:

- `visa_product_id`
- `title`
- `description`
- `sort_order`

Purpose:

- Requirement checklist per visa product

### `visa_documents`

Fields:

- `visa_product_id`
- `name`
- `description`
- `is_required`
- `sort_order`

Purpose:

- Defines document expectations that become `ApplicationDocument` records during submission

### `visa_process_steps`

Fields:

- `visa_product_id`
- `title`
- `description`
- `sort_order`

Purpose:

- Public-facing process explanation per visa product

### `visa_faqs`

Fields:

- `visa_product_id`
- `question`
- `answer`
- `sort_order`

Purpose:

- Product-specific FAQ content

### `visa_addons`

Fields:

- `visa_product_id`
- `name`
- `description`
- `price`
- `is_active`
- `sort_order`

Purpose:

- Optional upsell items that can be selected during application creation

## Application Workflow Domain

Defined primarily in:

- `2026_05_01_000400_create_application_workflow_tables.php`
- `2026_05_05_074825_add_wizard_fields_to_applications_table.php`
- `2026_05_07_103738_add_uuid_to_applications_table.php`

### `applications`

Core fields:

- `user_id`
- `visa_product_id`
- `reference_number`
- `status`
- `traveler_name`
- `traveler_email`
- `traveler_phone`
- `notes`
- `submitted_at`

Observed runtime usage also includes structured metadata such as:

- Departure date
- Delivery method
- Hard-file handling options
- Payment method id
- Selected add-ons
- Processing time type
- Price breakdown

Purpose:

- Main workflow record representing one client visa application

### `application_documents`

Fields:

- `application_id`
- `visa_document_id` nullable
- `label`
- `file_path`
- `status`
- `admin_feedback`
- `reviewed_at`

Purpose:

- Tracks file submission and review state for each document item in an application

### `application_status_logs`

Fields:

- `application_id`
- `admin_id` nullable
- `from_status`
- `to_status`
- `message`

Purpose:

- Audit trail of application status changes

### `application_messages`

Fields:

- `application_id`
- `sender_id`
- `is_admin`
- `message`
- `read_at`

Purpose:

- Communication channel between client and admin within an application context

## Content Domain

Defined in:

- `2026_05_01_000500_create_landing_content_tables.php`

### `testimonials`

Fields:

- `name`
- `visa_label`
- `rating`
- `content`
- `sort_order`
- `is_active`

Purpose:

- Public trust-building content used on landing surfaces

### `site_faqs`

Fields:

- `question`
- `answer`
- `sort_order`
- `is_active`

Purpose:

- Site-wide FAQ content not tied to one specific visa product

## Payments Domain

Defined in:

- `2026_05_05_084313_create_payment_methods_table.php`
- `2026_05_06_000000_add_configuration_to_payment_methods_table.php`
- `2026_05_06_022455_add_configuration_to_payment_methods_table.php`

### `payment_methods`

Observed purpose:

- Stores the payment methods presented during application creation and checkout
- Supports active/inactive filtering
- Supports extra configuration storage

## Supporting Infrastructure Tables

- `jobs`
- `cache`

These are framework-level support tables rather than product entities.

## Relationship Summary

```text
Country -> VisaProduct -> VisaRequirement
                     -> VisaDocument
                     -> VisaProcessStep
                     -> VisaFaq
                     -> VisaAddon

User -> Application -> ApplicationDocument
                   -> ApplicationStatusLog
                   -> ApplicationMessage

SiteFaq
Testimonial
PaymentMethod
```
