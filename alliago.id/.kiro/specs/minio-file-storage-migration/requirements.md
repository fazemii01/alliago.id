# Requirements Document

## Introduction

This feature migrates all file storage in the ALLIAGO Laravel application from the
local `public` disk to a MinIO (S3-compatible) object store. Every file uploaded by
any role (client documents, payment proofs, application documents, and admin-managed
assets such as payment method icons) must be stored in a single MinIO bucket named
`alliago`. The application already defines an S3 disk in `config/filesystems.php` that
is MinIO-compatible (it honors `AWS_ENDPOINT`, `AWS_URL`, and
`AWS_USE_PATH_STYLE_ENDPOINT`), so this migration centers on selecting that disk for all
storage operations, ensuring file URLs resolve correctly against MinIO, configuring the
required environment variables, and handling files that already exist on the legacy local
disk.

The current upload and display points identified in the codebase are:

- `ClientApplicationController::store` — stores application documents to
  `applications/{id}` on the `public` disk.
- `ClientDocumentController::store` — stores replacement documents to
  `application-documents` on the `public` disk and deletes prior files from the `public`
  disk.
- `ClientCheckoutController::store` — stores manual payment proofs to
  `payments/{id}` on the `public` disk.
- `PaymentMethodResource` — a Filament `FileUpload` for the `icon` field using the
  default disk and `payment-methods` directory.
- `ApplicationResource` and `checkout.blade.php` — render file URLs via `Storage::url(...)`.

## Glossary

- **Application**: The Laravel/Filament system under migration (the ALLIAGO web application).
- **MinIO_Store**: The S3-compatible object storage service that holds all uploaded files.
- **Storage_Bucket**: The single MinIO bucket named `alliago` that contains all uploaded files.
- **Storage_Disk**: The Laravel filesystem disk (driver `s3`) configured to target the MinIO_Store and Storage_Bucket.
- **Upload_Handler**: Any Application code path that persists an uploaded file (controllers and Filament resources listed in the Introduction).
- **File_Reference**: The relative object key (path within the Storage_Bucket) persisted in the database for a stored file.
- **URL_Resolver**: The Application logic that converts a File_Reference into a publicly reachable URL for display or download.
- **Legacy_File**: A file that was stored on the local `public` disk before this migration.
- **Configuration**: The environment variables and `config/filesystems.php` settings that define the Storage_Disk.
- **Uploaded_File**: Any file submitted by any role (client document, payment proof, application document, payment method icon, or any other uploaded asset).

## Requirements

### Requirement 1: Centralized MinIO Storage Disk Configuration

**User Story:** As a developer, I want a single configured Storage_Disk that targets MinIO and the `alliago` bucket, so that all file operations resolve to one consistent location.

#### Acceptance Criteria

1. THE Configuration SHALL define a Storage_Disk that uses the `s3` driver targeting the MinIO_Store.
2. THE Configuration SHALL set the Storage_Disk bucket value to `alliago`.
3. THE Configuration SHALL set the Storage_Disk endpoint value from the `AWS_ENDPOINT` environment variable.
4. THE Configuration SHALL set the Storage_Disk path-style addressing value from the `AWS_USE_PATH_STYLE_ENDPOINT` environment variable, and SHALL default path-style addressing to disabled when that variable is not set.
5. WHERE the `AWS_URL` environment variable is defined, THE Configuration SHALL use the `AWS_URL` value as the base for public file URLs of objects stored in the Storage_Bucket.
6. IF the `AWS_URL` environment variable is not defined, THEN THE Configuration SHALL produce public file URLs that target the MinIO_Store endpoint rather than the legacy local disk.

### Requirement 2: Environment Variable Definitions

**User Story:** As an operator, I want the MinIO connection variables documented in the environment files, so that I can configure each deployment without reading source code.

#### Acceptance Criteria

1. THE Configuration SHALL include `AWS_ENDPOINT` as a key with an accompanying comment describing its purpose in `.env.example`.
2. THE Configuration SHALL include `AWS_URL` as a key with an accompanying comment describing its purpose in `.env.example`.
3. THE Configuration SHALL set the `AWS_BUCKET` environment variable default to `alliago` in `.env.example`.
4. THE Configuration SHALL set the `AWS_USE_PATH_STYLE_ENDPOINT` environment variable to `true` in `.env.example`.
5. THE Configuration SHALL set the `FILESYSTEM_DISK` environment variable to `s3` (the MinIO Storage_Disk identifier) in `.env.example`.
6. THE Configuration SHALL group the MinIO connection variables under a single labeled comment section in `.env.example`.

### Requirement 3: Client Application Document Uploads to MinIO

**User Story:** As a client, I want the documents I submit when creating an application to be stored in MinIO, so that my files are retained in the central object store.

#### Acceptance Criteria

1. WHEN a client submits an application with one or more attached documents that are each of a supported type (PDF, JPG, JPEG, or PNG) and no larger than 5 MB (5120 KB), THE Upload_Handler SHALL store each document in the Storage_Bucket.
2. WHEN the Upload_Handler stores an application document, THE Upload_Handler SHALL persist the resulting File_Reference to the application document record.
3. WHEN the Upload_Handler stores an application document, THE Upload_Handler SHALL place the file under an object key namespaced by the application identifier.
4. IF an application is submitted without a file for an optional document, THEN THE Upload_Handler SHALL persist an empty File_Reference and the document's existing status without contacting the MinIO_Store.
5. WHEN a file is provided for an optional document, THE Upload_Handler SHALL validate that the file is of a supported type (PDF, JPG, JPEG, or PNG) and no larger than 5 MB (5120 KB) before attempting to store it in the Storage_Bucket.
6. IF storing any submitted application document fails, THEN THE Upload_Handler SHALL reject the application submission, surface an error to the client, and retain no File_Reference pointing to a non-existent object.
7. IF a submitted application document is of an unsupported type or larger than 5 MB (5120 KB), THEN THE Upload_Handler SHALL reject the submission with a validation error to the client without storing any file in the Storage_Bucket.

### Requirement 4: Client Document Replacement Uploads to MinIO

**User Story:** As a client, I want replacement documents I upload to be stored in MinIO and prior versions removed, so that only the current file is retained in the object store.

#### Acceptance Criteria

1. WHEN a client uploads a replacement document, THE Upload_Handler SHALL store the new file in the Storage_Bucket.
2. WHEN the Upload_Handler stores a replacement document, THE Upload_Handler SHALL persist the resulting File_Reference to the document record.
3. IF a document record holds a non-empty File_Reference at the time of replacement, THEN THE Upload_Handler SHALL delete the prior file from the Storage_Bucket only after the new file has been successfully stored and before persisting the new File_Reference.
4. WHERE a document record holds an empty File_Reference at the time of replacement, THE Upload_Handler SHALL store the new file and persist its File_Reference as a first upload.
5. IF storing a replacement document fails, THEN THE Upload_Handler SHALL return an error response indicating the replacement failed and SHALL leave the existing document record's File_Reference unchanged and the prior file retained in the Storage_Bucket.
6. IF deletion of the prior file fails after the new file has been successfully stored, THEN THE Upload_Handler SHALL persist the new File_Reference and complete the replacement without returning an error to the client.
7. IF the prior File_Reference references an object that no longer exists in the Storage_Bucket when deletion is attempted, THEN THE Upload_Handler SHALL treat the prior-file deletion as successful and persist the new File_Reference.

### Requirement 5: Payment Proof Uploads to MinIO

**User Story:** As a client paying manually, I want my payment proof to be stored in MinIO, so that the admin can verify my payment from the central object store.

#### Acceptance Criteria

1. WHEN a client submits a manual payment proof file that is present and no larger than 10 MB (10,485,760 bytes), THE Upload_Handler SHALL store the file in the Storage_Bucket.
2. WHEN the Upload_Handler stores a payment proof, THE Upload_Handler SHALL place the file under an object key namespaced by the application identifier.
3. WHEN the Upload_Handler stores a payment proof, THE Upload_Handler SHALL persist the resulting File_Reference to the application metadata.
4. IF a client submits a manual payment proof that is missing or larger than 10 MB (10,485,760 bytes), THEN THE Upload_Handler SHALL reject the submission with an error indicating the file is invalid before contacting the MinIO_Store.
5. IF storing a payment proof in the Storage_Bucket fails, THEN THE Upload_Handler SHALL notify the client with an error indicating the upload failed, require the client to resubmit the proof, and leave the application metadata without a File_Reference to the failed object.

### Requirement 6: Admin Payment Method Icon Uploads to MinIO

**User Story:** As an administrator, I want payment method icons I upload through the admin panel to be stored in MinIO, so that all uploaded assets reside in the central object store.

#### Acceptance Criteria

1. WHEN an administrator uploads a valid image file as a payment method icon, THE Upload_Handler SHALL store the icon in the Storage_Bucket.
2. WHEN the Upload_Handler stores a payment method icon, THE Upload_Handler SHALL place the file under the `payment-methods` object key prefix.
3. WHEN the Upload_Handler stores a payment method icon, THE Upload_Handler SHALL persist the resulting File_Reference to the payment method record.
4. IF storing a payment method icon fails, THEN THE Upload_Handler SHALL surface an error to the administrator indicating the storage failure, leave the payment method record without a File_Reference pointing to a non-existent object, and not retry the storage operation.
5. IF an administrator submits a payment method icon that is not a valid image file, THEN THE Upload_Handler SHALL reject the upload and surface a validation error to the administrator without storing the file in the Storage_Bucket.
6. IF an administrator saves a payment method without providing an icon file, THEN THE Upload_Handler SHALL persist an empty File_Reference without contacting the MinIO_Store.

### Requirement 7: File URL Resolution Against MinIO

**User Story:** As a user viewing stored files, I want file links and images to resolve against MinIO, so that I can view and download documents, payment proofs, and icons.

#### Acceptance Criteria

1. WHEN the URL_Resolver is given a non-empty File_Reference for a file in the Storage_Bucket, THE URL_Resolver SHALL produce a URL whose base equals the configured MinIO public base URL (the `AWS_URL` value) and whose path contains the File_Reference object key.
2. WHEN an administrator views an application document with a non-empty File_Reference in the admin panel, THE Application SHALL render a link whose target equals the URL produced by the URL_Resolver for that File_Reference.
3. WHEN a client views a payment method icon with a non-empty File_Reference on the checkout page, THE Application SHALL render an image whose source equals the URL produced by the URL_Resolver for that File_Reference.
4. THE URL_Resolver SHALL produce a URL whose base equals the configured MinIO public base URL for any given File_Reference without contacting the MinIO_Store to verify whether a corresponding object exists in the Storage_Bucket.
5. IF the URL_Resolver is given an empty File_Reference, THEN THE URL_Resolver SHALL return an empty result without producing a URL targeting the MinIO_Store.

### Requirement 8: Uniform Storage Across All Upload Handlers

**User Story:** As a developer, I want every Upload_Handler to use the single MinIO Storage_Disk, so that no file is written to the legacy local disk after migration.

#### Acceptance Criteria

1. THE Upload_Handler SHALL write every Uploaded_File to the MinIO Storage_Disk.
2. THE Upload_Handler SHALL write no Uploaded_File to the legacy local disk.
3. WHEN an Upload_Handler deletes a stored file, THE Upload_Handler SHALL delete that file from the MinIO Storage_Disk.
4. WHEN the Application resolves a stored-file URL from a File_Reference, THE Application SHALL produce a URL whose base targets the MinIO_Store configured for the Storage_Disk.
5. THE Application SHALL derive each stored-file URL from its File_Reference through the MinIO Storage_Disk rather than reusing any previously persisted URL, so that no produced stored-file URL targets the legacy local disk.

### Requirement 9: Legacy File Handling

**User Story:** As an operator, I want a defined behavior for files already stored on the local public disk, so that previously uploaded files remain accessible after the migration.

#### Acceptance Criteria

1. WHEN a Legacy_File is transferred to the Storage_Bucket, THE Application SHALL store the Legacy_File under the identical File_Reference object key it held on the local `public` disk, so existing database records resolve without modification.
2. WHEN the legacy-file transfer process runs and a Legacy_File already exists in the Storage_Bucket under its File_Reference object key with content identical to the source Legacy_File, THE Application SHALL skip re-transferring that Legacy_File and leave the existing object unchanged, so that repeated runs produce a Storage_Bucket state identical to a single transfer.
3. IF a Legacy_File transfer fails for an individual file, THEN THE Application SHALL record the failing File_Reference together with a failure indication and continue transferring the remaining Legacy_Files without aborting the transfer process.
4. IF recording a Legacy_File transfer failure does not itself succeed, THEN THE Application SHALL continue transferring the remaining Legacy_Files without aborting the transfer process.
5. WHEN a Legacy_File is transferred successfully to the Storage_Bucket, THE Application SHALL retain the source Legacy_File on the local `public` disk unchanged.
6. WHEN the legacy-file transfer process completes, THE Application SHALL report the count of Legacy_Files transferred successfully and the count of Legacy_Files that failed to transfer.

### Requirement 10: Upload Failure Handling

**User Story:** As a user, I want a clear outcome when a file cannot be stored in MinIO, so that I am not left believing a failed upload succeeded.

#### Acceptance Criteria

1. IF an Upload_Handler does not receive a successful store confirmation from the MinIO_Store within 30 seconds of attempting to store an Uploaded_File, THEN THE Upload_Handler SHALL treat the storage attempt as failed.
2. IF storing an Uploaded_File fails, THEN THE Upload_Handler SHALL NOT persist a File_Reference for that Uploaded_File to the associated database record.
3. IF storing an Uploaded_File fails for any cause, THEN THE Upload_Handler SHALL return to the requesting user, within 30 seconds of the failure being detected, an error response that indicates the Uploaded_File was not stored.
4. IF storing an Uploaded_File fails, THEN THE Upload_Handler SHALL leave the associated database record in the same state it held before the storage attempt.
