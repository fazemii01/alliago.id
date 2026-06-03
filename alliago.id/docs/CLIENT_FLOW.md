# Client Flow

## Overview

The client flow covers account entry, application submission, document handling, checkout, and ongoing application visibility.

## 1. Account Entry

A visitor can:

- Open `/client/login`
- Open `/client/register`
- Authenticate into the client area

The default `/login` route redirects to `/client/login`.

## 2. Start Application

An authenticated client can open:

- `/client/applications/create/{visaProduct:slug}`

Current behavior:

- Only active visa products can be used
- The page loads the visa product with `country`, `documents`, and `addons`
- Active payment methods are loaded for selection

## 3. Submit Application

The application submission route is:

- `POST /client/applications/{visaProduct:slug}`

Current required input includes:

- Traveler name
- Traveler email
- Delivery method
- Payment method
- Processing time type
- Subtotal
- Tax
- Total

Optional input includes:

- Traveler phone
- Notes
- Departure date
- Hard-copy delivery fields when applicable
- Add-ons
- Uploaded documents

## 4. Creation Side Effects

When an application is submitted, the system:

1. Verifies the visa product is active
2. Validates the request payload
3. Creates an `Application`
4. Assigns a `GP-` prefixed reference number
5. Sets status to `pending_payment`
6. Stores metadata with pricing and workflow selections
7. Creates an `ApplicationStatusLog`
8. Creates related `ApplicationDocument` records for the visa product documents
9. Stores uploaded files when present
10. Redirects the client to checkout

## 5. Checkout

The client can open:

- `GET /client/applications/{application}/checkout`

And submit:

- `POST /client/applications/{application}/checkout`

The checkout layer exists to complete payment for applications in `pending_payment` status.

## 6. Application Detail Access

The client can open:

- `GET /client/applications/{application}`

Current rules:

- Ownership is enforced by checking `user_id === auth()->id()`
- If the application status is `pending_payment`, the user is redirected to checkout
- Unread admin messages are marked as read when the page is opened

The application detail page loads:

- Visa product and country
- Documents
- Messages and senders
- Status logs

## 7. Document Uploads

The client can upload documents through:

- `POST /client/applications/{application}/documents/{document}`

Current document rules from application creation logic:

- Supported file types: PDF, JPG, JPEG, PNG
- Maximum size: 5 MB
- Files are stored on the `public` disk

Document statuses currently used include:

- `pending_upload`
- `uploaded`
- `optional`

## 8. Messaging

The client can send application-linked messages through:

- `POST /client/applications/{application}/messages`

This supports direct communication inside the application workflow.

## 9. Invoice Access

The client can open:

- `GET /client/applications/{application}/invoice`

This provides invoice visibility as part of the payment lifecycle.

## 10. Profile Management

The client can:

- Open `/client/profile`
- Update profile via `PATCH /client/profile`
- Update password via `PUT /client/password`

## Lifecycle Summary

```text
Browse visa product
Register or login
Open application create page
Submit traveler data, pricing, and delivery options
Upload documents
Proceed to checkout
Pay
Review invoice
Track messages and status logs
```
