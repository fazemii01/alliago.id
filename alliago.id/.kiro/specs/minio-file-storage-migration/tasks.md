# Implementation Plan: MinIO File Storage Migration

## Overview

This plan implements the migration of all file storage from the local `public` disk to a
MinIO (S3-compatible) object store, following the approved design. The work proceeds
foundation-first: configure the `s3` disk and environment, add the property-based testing
tooling, build the central `FileStorage` service, then refactor each Upload_Handler and
URL_Resolver call site to route through it, and finally add the idempotent
`storage:migrate-legacy` Artisan command. Each correctness property from the design is
implemented as exactly one property-based test, tagged
`Feature: minio-file-storage-migration, Property {n}: {text}`, and placed close to the code
it validates so failures surface early.

Implementation language: **PHP (Laravel 12 + Filament 3, PHPUnit 11)** — taken directly
from the design; no pseudocode was used.

## Tasks

- [x] 1. Configure the MinIO storage disk, environment, and test tooling
  - [x] 1.1 Add the property-based testing library as a dev dependency
    - Add `innmind/black-box` (preferred) to `require-dev` in `composer.json`; if it cannot be installed, fall back to `giorgiosironi/eris`
    - Run the package install so the library autoloads under the `Tests\` namespace
    - Do not hand-roll generators; the chosen library MUST be used for all property tests
    - _Requirements: (testing tooling for Properties 1-9)_

  - [x] 1.2 Rewrite the `s3` disk definition in `config/filesystems.php`
    - Derive `endpoint` from `AWS_ENDPOINT` with fallback to `scheme(MINIO_SECURE) + "://" + MINIO_ENDPOINT`
    - Derive `bucket` from `AWS_BUCKET` with fallback to `MINIO_BUCKET_NAME` (default `alliago`)
    - Derive public `url` from `AWS_URL` with fallback to `{endpoint}/{bucket}`
    - Source `key`/`secret` from `AWS_*` with `MINIO_ACCESS_KEY`/`MINIO_SECRET_KEY` fallback; default `region` `us-east-1`
    - Set `use_path_style_endpoint` from `AWS_USE_PATH_STYLE_ENDPOINT` defaulting to `true`
    - Set `throw => true` and `options.http` `connect_timeout`/`timeout` to bound stores at ~30s
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 10.1_

  - [x] 1.3 Update `.env.example` with the labeled MinIO section
    - Add a single labeled comment block grouping the MinIO connection variables
    - Set `FILESYSTEM_DISK=s3`, `AWS_BUCKET=alliago`, `AWS_USE_PATH_STYLE_ENDPOINT=true`
    - Document `AWS_ENDPOINT` and `AWS_URL` with explanatory comments, and the canonical `MINIO_*` keys (`MINIO_ENDPOINT`, `MINIO_ACCESS_KEY`, `MINIO_SECRET_KEY`, `MINIO_BUCKET_NAME=alliago`, `MINIO_SECURE=false`)
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6_

  - [ ]* 1.4 Write smoke tests for disk configuration and `.env.example`
    - Assert the `s3` disk uses driver `s3`, bucket resolves to `alliago`, path-style defaults to `true`, and the client timeout is 30s
    - Assert `.env.example` contains the labeled MinIO section with `FILESYSTEM_DISK=s3`, `AWS_ENDPOINT`, `AWS_URL`, `AWS_BUCKET=alliago`, `AWS_USE_PATH_STYLE_ENDPOINT=true`
    - Place in `tests/Feature/MinioConfigSmokeTest.php`
    - _Requirements: 1.1, 1.2, 1.4, 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 10.1_

  - [ ]* 1.5 Write property test for MinIO endpoint derivation
    - **Property 1: MinIO endpoint derivation** — for any host:port and any `MINIO_SECURE` boolean, when `AWS_ENDPOINT` is absent the derived endpoint equals `("https" if secure else "http") + "://" + host:port`; for any non-empty `AWS_ENDPOINT` the derived endpoint equals that override exactly
    - **Validates: Requirements 1.3, 1.6**
    - Tag: `Feature: minio-file-storage-migration, Property 1: MinIO endpoint derivation`
    - Place in `tests/Unit/MinioConfigPropertyTest.php`; run ≥100 iterations
    - _Requirements: 1.3, 1.6_

- [x] 2. Implement the central FileStorage service
  - [x] 2.1 Create the `App\Contracts\FileStorage` interface
    - Declare `store(UploadedFile $file, string $directory): string` (returns the object key, throws on failure)
    - Declare `delete(?string $key): bool` (idempotent), `url(?string $key): string` (empty key → `''`), and `exists(string $key): bool`
    - Place in `app/Contracts/FileStorage.php`
    - _Requirements: 8.1, 8.3, 8.4_

  - [x] 2.2 Implement `App\Services\FileStorageService`
    - Back the service with `Storage::disk()` using the configured default/`s3` disk
    - `store()` uses `putFile($directory, $file)` and returns the generated key; surface store failures as exceptions
    - `url()` short-circuits empty/blank/whitespace-only keys to `''` before delegating to the disk's `url()`
    - `delete()` treats a missing object as success (idempotent) and never targets the legacy local disk
    - Place in `app/Services/FileStorageService.php`
    - _Requirements: 7.1, 7.4, 7.5, 4.7, 8.1, 8.4, 8.5_

  - [x] 2.3 Bind `FileStorageService` as a singleton in `AppServiceProvider`
    - Bind `App\Contracts\FileStorage` to `App\Services\FileStorageService` as a singleton in `app/Providers/AppServiceProvider.php`
    - Ensure the binding is resolvable for constructor injection into controllers
    - _Requirements: 8.1, 8.4_

  - [ ]* 2.4 Write property test for URL resolution base
    - **Property 2: URL resolution targets the MinIO public base** — for any non-empty File_Reference, the resolver returns a URL whose base equals the configured public base (`AWS_URL` when set, else `endpoint + "/" + bucket`) and whose path contains the key, without contacting MinIO and never targeting the legacy local disk base
    - **Validates: Requirements 1.5, 7.1, 7.4, 8.4, 8.5**
    - Tag: `Feature: minio-file-storage-migration, Property 2: URL resolution targets the MinIO public base`
    - Place in `tests/Unit/FileStorageUrlPropertyTest.php`; run ≥100 iterations
    - _Requirements: 1.5, 7.1, 7.4, 8.4, 8.5_

  - [ ]* 2.5 Write property test for empty File_Reference resolution
    - **Property 3: Empty File_Reference resolves to an empty URL** — for any empty, `null`, or whitespace-only File_Reference, the resolver returns an empty result and produces no URL targeting MinIO
    - **Validates: Requirements 7.5**
    - Tag: `Feature: minio-file-storage-migration, Property 3: Empty File_Reference resolves to an empty URL`
    - Add to `tests/Unit/FileStorageUrlPropertyTest.php`; run ≥100 iterations
    - _Requirements: 7.5_

  - [ ]* 2.6 Write property test for delete idempotency
    - **Property 6: Delete is idempotent** — for any object key and any disk state (present or absent), `FileStorage::delete` reports success and the key is absent afterwards
    - **Validates: Requirements 4.7**
    - Tag: `Feature: minio-file-storage-migration, Property 6: Delete is idempotent`
    - Place in `tests/Unit/FileStorageDeletePropertyTest.php` using `Storage::fake('s3')`; run ≥100 iterations
    - _Requirements: 4.7_

  - [ ]* 2.7 Write unit tests for `FileStorageService`
    - Cover `store()` returning a usable key, `exists()` after store, and the empty-key/whitespace `url()` branches as concrete examples
    - Place in `tests/Unit/FileStorageServiceTest.php` using `Storage::fake('s3')`
    - _Requirements: 7.5, 8.1_

- [ ] 3. Checkpoint - configuration and service layer
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 4. Refactor upload handlers to use the MinIO storage service
  - [ ] 4.1 Refactor `ClientApplicationController::store`
    - Wrap application + document creation in a DB transaction
    - Store each provided file via `FileStorage::store($file, "applications/{$application->id}")` and persist the returned key to the document record
    - For optional documents with no file, persist an empty File_Reference and the existing status without contacting MinIO
    - On any store exception, roll back the transaction, delete any objects already written this request, and redirect back with an error
    - Modify `app/Http/Controllers/ClientApplicationController.php`
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.6, 8.1, 8.2, 10.2, 10.4_

  - [ ] 4.2 Refactor `ClientDocumentController::store`
    - Store the new file first via `FileStorage::store(...)` inside try/catch; on failure return an error and leave the record and prior file untouched
    - On success, attempt `FileStorage::delete($priorKey)` only after the new file is stored, swallowing delete errors and treating a missing object as success, then persist the new key
    - Handle the empty-prior-reference case as a first upload
    - Modify `app/Http/Controllers/ClientDocumentController.php`
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 4.7, 8.1, 8.2, 8.3, 10.2, 10.4_

  - [ ] 4.3 Refactor `ClientCheckoutController::store`
    - Raise `payment_proof` validation to `max:10240` (10 MB) and keep required/mimes rules
    - Store via `FileStorage::store($file, "payments/{$application->id}")` in try/catch; persist the key to application metadata on success
    - On store failure return an error requiring resubmission and leave `metadata`/`status` unchanged
    - Modify `app/Http/Controllers/ClientCheckoutController.php`
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5, 8.1, 8.2, 10.2, 10.4_

  - [ ] 4.4 Update `PaymentMethodResource` icon `FileUpload` to use disk `s3`
    - Add `->disk('s3')` to the `FileUpload::make('icon')` component and keep `->image()` validation and the `payment-methods` directory
    - Rely on disk `throw => true` to surface storage failures to the admin without retry; no icon persists an empty File_Reference
    - Modify `app/Filament/Resources/PaymentMethodResource.php`
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5, 6.6, 8.1, 8.2_

  - [ ]* 4.5 Write property test for stored-key namespacing
    - **Property 4: Stored keys are namespaced by application identifier** — for any application id and any upload filename, an application-document key begins with `applications/{id}/` and a payment-proof key begins with `payments/{id}/`
    - **Validates: Requirements 3.3, 5.2**
    - Tag: `Feature: minio-file-storage-migration, Property 4: Stored keys are namespaced by application identifier`
    - Place in `tests/Feature/UploadNamespacingPropertyTest.php` using `Storage::fake('s3')` and `UploadedFile::fake()`; run ≥100 iterations
    - _Requirements: 3.3, 5.2_

  - [ ]* 4.6 Write property test for upload validation rejection
    - **Property 5: Upload validation rejects unsupported files without storing** — for any candidate upload, the handler accepts it only when it satisfies the field constraints (documents: type in {pdf, jpg, jpeg, png} and ≤5120 KB; payment proof: present and ≤10,485,760 bytes; icon: valid image), and rejects violations with a validation error while writing nothing to the bucket
    - **Validates: Requirements 3.5, 3.7, 5.4, 6.5**
    - Tag: `Feature: minio-file-storage-migration, Property 5: Upload validation rejects unsupported files without storing`
    - Place in `tests/Feature/UploadValidationPropertyTest.php` generating type/size around boundaries and image/non-image; run ≥100 iterations
    - _Requirements: 3.5, 3.7, 5.4, 6.5_

  - [ ]* 4.7 Write property test for failed-store database invariance
    - **Property 9: Failed storage leaves the database unchanged** — for any application submission in which storing at least one document fails, the database contains exactly the records it held before the attempt (no document rows persisted, no dangling File_Reference)
    - **Validates: Requirements 3.6, 10.2, 10.4**
    - Tag: `Feature: minio-file-storage-migration, Property 9: Failed storage leaves the database unchanged`
    - Place in `tests/Feature/ApplicationStoreFailurePropertyTest.php` with an injected failing store over generated document sets; run ≥100 iterations
    - _Requirements: 3.6, 10.2, 10.4_

  - [ ]* 4.8 Write example tests for application document store
    - Cover document store + persist, optional-doc no-file empty reference, and submission rejection on store failure
    - Place in `tests/Feature/ClientApplicationStoreTest.php` using `Storage::fake('s3')` + `Storage::fake('public')`; assert nothing is written to the local disk
    - _Requirements: 3.1, 3.2, 3.4, 3.6, 8.1, 8.2, 10.2_

  - [ ]* 4.9 Write example tests for document replacement
    - Cover store/persist/order (delete prior only after successful store), store-failure leaving record + prior file intact, and tolerated prior-delete failure
    - Place in `tests/Feature/ClientDocumentReplacementTest.php` using `Storage::fake('s3')` + `Storage::fake('public')`
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 8.1, 8.2, 8.3_

  - [ ]* 4.10 Write example tests for payment proof upload
    - Cover proof store + persist to metadata and proof store-failure leaving metadata/status unchanged with an error response
    - Place in `tests/Feature/ClientCheckoutStoreTest.php` using `Storage::fake('s3')` + `Storage::fake('public')`
    - _Requirements: 5.1, 5.3, 5.5, 8.1, 8.2, 10.2, 10.3_

  - [ ]* 4.11 Write example tests for payment method icon upload
    - Cover icon store under the `payment-methods` prefix + persist, store-failure with no retry, and no-icon empty reference
    - Place in `tests/Feature/PaymentMethodIconTest.php` using `Storage::fake('s3')`
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.6_

- [ ] 5. Checkpoint - upload handlers migrated
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 6. Update URL resolution at display points
  - [ ] 6.1 Route `ApplicationResource` document placeholder through the resolver
    - Replace `Storage::url($record->file_path)` with `FileStorage::url($record->file_path)` so empty keys render "No file uploaded" and non-empty keys resolve to MinIO
    - Modify `app/Filament/Resources/ApplicationResource.php`
    - _Requirements: 7.1, 7.2, 7.5, 8.4, 8.5_

  - [ ] 6.2 Resolve the checkout payment-method icon against MinIO
    - Replace `Storage::url($paymentMethod->icon)` with the `FileStorage::url(...)` resolver (or `Storage::disk('s3')->url(...)`) for the icon `img` source
    - Modify the checkout blade view (`resources/views/**/checkout.blade.php`)
    - _Requirements: 7.1, 7.3, 8.4, 8.5_

  - [ ] 6.3 Set the `PaymentMethodResource` table `ImageColumn` to disk `s3`
    - Add `->disk('s3')` to `ImageColumn::make('icon')` so the admin thumbnail resolves from MinIO
    - Modify `app/Filament/Resources/PaymentMethodResource.php`
    - _Requirements: 7.1, 8.4_

  - [ ]* 6.4 Write example tests for URL rendering at display points
    - Assert the admin application-document link target equals the resolver URL and the checkout icon `img` source equals the resolver URL for a non-empty reference
    - Place in `tests/Feature/UrlResolutionRenderTest.php`
    - _Requirements: 7.2, 7.3_

- [ ] 7. Implement the legacy file migration command
  - [ ] 7.1 Create the `storage:migrate-legacy` Artisan command
    - Implement `App\Console\Commands\MigrateLegacyFiles` (signature `storage:migrate-legacy {--dry-run}`) in `app/Console/Commands/MigrateLegacyFiles.php`
    - Iterate every file on the `public` disk; write each to the `s3` disk under the identical key via streams
    - Skip files already present in the bucket with identical content (compare size first, then MD5/ETag), leaving the existing object byte-for-byte unchanged
    - Wrap each file in try/catch with an inner guarded try/catch around logging so the loop never aborts; increment success/failure counts; never delete or modify the source
    - Report success and failure counts on completion; support `--dry-run` to list transfers without writing
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5, 9.6_

  - [ ]* 7.2 Write property test for legacy transfer fidelity and source retention
    - **Property 7: Legacy transfer fidelity and source retention** — for any set of files on the local `public` disk, after the command runs each file exists in the bucket under its identical key with identical content, and each source file remains present and unchanged on the local disk
    - **Validates: Requirements 9.1, 9.5**
    - Tag: `Feature: minio-file-storage-migration, Property 7: Legacy transfer fidelity and source retention`
    - Place in `tests/Feature/LegacyTransferPropertyTest.php` over generated {key, content} sets on fake `public`; run ≥100 iterations
    - _Requirements: 9.1, 9.5_

  - [ ]* 7.3 Write property test for legacy transfer idempotence
    - **Property 8: Legacy transfer is idempotent** — for any set of files on the local `public` disk, running the command twice leaves the bucket in a state identical (same keys, same content) to running it once, and any pre-existing matching object is left byte-for-byte unchanged
    - **Validates: Requirements 9.2**
    - Tag: `Feature: minio-file-storage-migration, Property 8: Legacy transfer is idempotent`
    - Add to `tests/Feature/LegacyTransferPropertyTest.php` with a double-run over generated {key, content} sets; run ≥100 iterations
    - _Requirements: 9.2_

  - [ ]* 7.4 Write example tests for the migration command
    - Cover failure recording + continuation across remaining files and the reported success/failure counts
    - Place in `tests/Feature/MigrateLegacyFilesTest.php` using `Storage::fake('s3')` + `Storage::fake('public')`
    - _Requirements: 9.3, 9.4, 9.6_

- [ ] 8. Final checkpoint - full migration verified
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional test sub-tasks and can be skipped for a faster MVP; core implementation sub-tasks are never optional.
- Each task references the specific requirements clauses (and, for property tests, the design property) it implements for traceability.
- Property-based tests use the configured PBT library (`innmind/black-box`, or `giorgiosironi/eris`) at ≥100 iterations and are tagged `Feature: minio-file-storage-migration, Property {n}: {text}`.
- Each of the 9 correctness properties maps to exactly one property-based test, placed close to the code it validates.
- Handler and transfer tests use `Storage::fake('s3')` and `Storage::fake('public')` so the default suite stays hermetic; live-MinIO integration checks are out of scope for these tasks.
- Checkpoints provide incremental validation between the configuration, handler, and command phases.

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1", "1.2", "1.3", "2.1"] },
    { "id": 1, "tasks": ["2.2", "1.4", "1.5"] },
    { "id": 2, "tasks": ["2.3", "2.4", "2.6", "2.7"] },
    { "id": 3, "tasks": ["2.5", "4.1", "4.2", "4.3", "4.4"] },
    { "id": 4, "tasks": ["4.5", "4.6", "4.7", "4.8", "4.9", "4.10", "4.11", "6.1", "6.2", "6.3", "7.1"] },
    { "id": 5, "tasks": ["6.4", "7.2", "7.4"] },
    { "id": 6, "tasks": ["7.3"] }
  ]
}
```
