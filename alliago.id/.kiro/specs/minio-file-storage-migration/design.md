# Design Document

## Overview

This design migrates all file storage in the ALLIAGO Laravel/Filament application from
the local `public` disk to a MinIO (S3-compatible) object store, using the existing
`s3` filesystem driver. Every Upload_Handler will write to a single configured
Storage_Disk that targets the `alliago` bucket, every URL_Resolver will produce URLs
against the MinIO endpoint, and a one-off, idempotent Artisan command will copy
pre-existing Legacy_Files from the local `public` disk into the bucket under identical
object keys.

The migration is overwhelmingly a **configuration + call-site standardization** effort.
No database schema changes are required: the `file_path` columns and the
`metadata.payment_proof_path` JSON value already store disk-relative object keys (e.g.
`applications/5/passport.pdf`), which are portable across disks. That portability is the
linchpin that lets legacy files resolve without touching the database (Requirement 9.1).

### Key discrepancies this design resolves

Three mismatches between the approved requirements, the current code, and the actual
deployment environment were found while reading the codebase. They are called out here
because the design must reconcile them, and two of them imply a recommended follow-up to
`requirements.md`.

**Discrepancy 1 — Environment variable naming (`AWS_*` vs `MINIO_*`).**
`requirements.md` (Requirement 1.3/1.5/1.6, Requirement 2, Requirement 7.1) and the
current `config/filesystems.php` `s3` disk read `AWS_*` variables
(`AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_BUCKET`, `AWS_URL`, `AWS_ENDPOINT`,
`AWS_USE_PATH_STYLE_ENDPOINT`). All of those are currently **empty** in `.env`. The
operator has instead populated a different set that points at a live MinIO server:
`MINIO_ENDPOINT`, `MINIO_ACCESS_KEY`, `MINIO_SECRET_KEY`, `MINIO_BUCKET_NAME`,
`MINIO_SECURE`. (Credentials are referenced here by variable name only; their values are
not reproduced.) This design **standardizes on `MINIO_*` as the source of truth** and
documents the mapping below. See "Configuration Reconciliation Decision" for the
justification and the exact precedence rule.

**Discrepancy 2 — Payment-proof size limit (10 MB vs 5 MB).**
Requirement 5.1/5.4 specify a 10 MB (10,485,760-byte) limit for manual payment proofs.
The current `ClientCheckoutController::store` validates `payment_proof` with `max:5120`
(5 MB) and `checkout.blade.php` displays "Maksimal 5MB". To satisfy the approved
requirement, this design raises the validation to `max:10240` and updates the helper
text. If 5 MB is actually intended, `requirements.md` Requirement 5 should be amended
instead — this is flagged as a requirements follow-up.

**Discrepancy 3 — Path-style default.**
Requirement 1.4 says path-style addressing SHALL default to *disabled* when the variable
is unset. MinIO, however, **requires** path-style addressing, and the operator's current
`AWS_USE_PATH_STYLE_ENDPOINT` is `false`. To produce a working MinIO configuration this
design defaults path-style to *enabled* (`true`) in `config/filesystems.php`. This is a
deliberate divergence from Requirement 1.4 in favor of a configuration that actually
works against MinIO; it is flagged as a requirements follow-up.

## Configuration Reconciliation Decision

**Decision: Option (a) — the `s3` disk sources its values from the `MINIO_*` variables,
with `AWS_*` retained as optional overrides.**

The two options posed were: (a) standardize on `MINIO_*` in `config/filesystems.php`, or
(b) copy the `MINIO_*` values into the `AWS_*` keys so the disk keeps reading `AWS_*`.
Option (a) is chosen for the following reasons:

1. **Single source of truth, no drift.** The MinIO server is already running and the
   operator has already populated `MINIO_*`. Option (b) would require maintaining two
   sets of variables describing the same server; they can silently diverge. Option (a)
   keeps exactly one canonical set.
2. **The endpoint scheme can only be derived, not copied.** `MINIO_ENDPOINT` is a bare
   `host:port` (`194.233.91.132:19000`) with no URL scheme; `MINIO_SECURE` carries the
   scheme decision (`false` → `http`, `true` → `https`). The full endpoint URL
   (`http://194.233.91.132:19000`) must be *computed* from those two values. Computing it
   in config removes a whole class of operator error (forgetting `http://`), which
   Option (b) would reintroduce because `AWS_ENDPOINT` is a single pre-assembled URL.
3. **Honors the existing, working deployment.** Option (a) makes the operator's current
   `.env` work as-is. Option (b) leaves the live values stranded in unused variables.

To preserve compatibility with the approved requirements (which name `AWS_*`) and with
the Laravel S3 convention, the disk reads `AWS_*` first and **falls back to the derived
`MINIO_*` value**: `env('AWS_ENDPOINT', $derivedMinioEndpoint)`. This means a future
cloud S3 deployment can still set `AWS_*` directly, while today's MinIO deployment works
purely from `MINIO_*`.

### Variable mapping (requirements `AWS_*` → actual `MINIO_*`)

| Disk setting (`s3`)         | Primary env (`AWS_*`)          | Fallback (`MINIO_*`)                         | Derived value example                  |
| --------------------------- | ------------------------------ | -------------------------------------------- | -------------------------------------- |
| `key`                       | `AWS_ACCESS_KEY_ID`            | `MINIO_ACCESS_KEY`                           | *(referenced by name only)*            |
| `secret`                    | `AWS_SECRET_ACCESS_KEY`        | `MINIO_SECRET_KEY`                           | *(referenced by name only)*            |
| `region`                    | `AWS_DEFAULT_REGION`           | — (default `us-east-1`)                      | `us-east-1`                            |
| `bucket`                    | `AWS_BUCKET`                   | `MINIO_BUCKET_NAME`                          | `alliago`                              |
| `endpoint`                  | `AWS_ENDPOINT`                 | scheme(`MINIO_SECURE`) + `://` + `MINIO_ENDPOINT` | `http://194.233.91.132:19000`     |
| `url` (public base)         | `AWS_URL`                      | derived `endpoint` + `/` + `bucket`          | `http://194.233.91.132:19000/alliago`  |
| `use_path_style_endpoint`   | `AWS_USE_PATH_STYLE_ENDPOINT`  | — (default `true`)                           | `true`                                 |

Because MinIO is addressed in path style, the public URL for an object key `K` is
`{endpoint}/{bucket}/{K}` — e.g. `http://194.233.91.132:19000/alliago/applications/5/passport.pdf`.
Setting the disk `url` to `{endpoint}/{bucket}` makes Laravel's `Storage::url($K)`
produce exactly that.

## Architecture

```mermaid
flowchart TD
    subgraph Handlers["Upload_Handlers"]
        A[ClientApplicationController::store]
        B[ClientDocumentController::store]
        C[ClientCheckoutController::store]
        D["PaymentMethodResource\n(Filament FileUpload)"]
    end

    subgraph Display["URL_Resolver call sites"]
        E["ApplicationResource\n(Placeholder link)"]
        F["checkout.blade.php\n(icon img)"]
        G["PaymentMethodResource table\n(ImageColumn)"]
    end

    FS["FileStorage service\n(App\\Services\\FileStorageService)"]
    DISK["Storage_Disk 's3'\n(config/filesystems.php)"]
    MINIO[("MinIO_Store\nbucket: alliago")]
    LOCAL[("Legacy local 'public' disk")]
    CMD["Artisan: storage:migrate-legacy"]

    A --> FS
    B --> FS
    C --> FS
    D -->|->disk('s3')| DISK
    FS --> DISK
    E --> FS
    F --> FS
    G -->|->disk('s3')| DISK
    DISK --> MINIO
    CMD -->|read| LOCAL
    CMD -->|write identical keys| MINIO
```

### Approach to uniformity (Requirement 8)

Two complementary mechanisms guarantee that no file is written to, or resolved from, the
legacy local disk after migration:

1. **Default disk = `s3`.** `FILESYSTEM_DISK` is set to `s3`, so any `Storage::*` call
   or Filament `FileUpload`/`ImageColumn` without an explicit disk uses MinIO.
2. **Explicit disk at every migrated call site.** Each of the handlers and display points
   listed in the requirements is changed to go through the central `FileStorage` service
   (which targets `s3`) or to name `s3` explicitly (Filament components). This removes the
   current hard-coded `'public'` arguments and the implicit default-disk usage in
   `Storage::url(...)`.

A thin central service (`FileStorageService`) is introduced rather than scattering
`Storage::disk('s3')` literals. It gives one seam to (a) enforce the disk, (b) implement
the empty-key URL rule (Requirement 7.5), (c) implement delete idempotency
(Requirement 4.7), and (d) unit/property-test the logic without a live MinIO.

## Components and Interfaces

### 1. `config/filesystems.php` — Storage_Disk definition

The `s3` disk is rewritten to derive its values per the mapping table. Sketch:

```php
$minioSecure   = filter_var(env('MINIO_SECURE', false), FILTER_VALIDATE_BOOLEAN);
$minioEndpoint = env('MINIO_ENDPOINT');                       // host:port, no scheme
$minioBucket   = env('MINIO_BUCKET_NAME', 'alliago');
$minioBaseUrl  = $minioEndpoint ? ($minioSecure ? 'https' : 'http').'://'.$minioEndpoint : null;
$minioPublicUrl = $minioBaseUrl ? $minioBaseUrl.'/'.$minioBucket : null;

's3' => [
    'driver'   => 's3',
    'key'      => env('AWS_ACCESS_KEY_ID', env('MINIO_ACCESS_KEY')),
    'secret'   => env('AWS_SECRET_ACCESS_KEY', env('MINIO_SECRET_KEY')),
    'region'   => env('AWS_DEFAULT_REGION', 'us-east-1'),
    'bucket'   => env('AWS_BUCKET', $minioBucket),
    'url'      => env('AWS_URL', $minioPublicUrl),
    'endpoint' => env('AWS_ENDPOINT', $minioBaseUrl),
    'use_path_style_endpoint' => filter_var(env('AWS_USE_PATH_STYLE_ENDPOINT', true), FILTER_VALIDATE_BOOLEAN),
    'throw'    => true,                                        // Requirement 10: surface failures
    'report'   => false,
    'options'  => [
        'http' => ['connect_timeout' => 10, 'timeout' => 30], // Requirement 10.1/10.3
    ],
],
```

Notes:
- `throw => true` (changed from `false`) makes write/delete failures raise
  `League\Flysystem\*` exceptions so handlers can detect failure and avoid persisting a
  dangling File_Reference (Requirements 3.6, 5.5, 6.4, 10.2).
- The `options.http` timeouts bound a stalled store at ~30 s so the handler can return a
  failure within the window (Requirements 10.1, 10.3). The AWS SDK `S3Client` receives
  these via the disk config.
- The endpoint-derivation block is a pure function of `MINIO_ENDPOINT` + `MINIO_SECURE`
  and is the subject of a correctness property.

### 2. `App\Contracts\FileStorage` (interface) and `App\Services\FileStorageService`

A single abstraction used by all controller-based Upload_Handlers and URL_Resolvers.

```php
interface FileStorage
{
    /** Store an uploaded file under $directory; return the object key. Throws on failure. */
    public function store(\Illuminate\Http\UploadedFile $file, string $directory): string;

    /** Delete an object key. Returns true when the object is gone afterwards,
     *  including when it never existed (idempotent — Requirement 4.7). */
    public function delete(?string $key): bool;

    /** Resolve a public URL for a key. Empty/blank key → '' (Requirement 7.5). */
    public function url(?string $key): string;

    public function exists(string $key): bool;
}
```

`FileStorageService` implements this against `Storage::disk($this->disk)` where
`$this->disk = config('filestorage.disk', 's3')` (a tiny new `config/filestorage.php`, or
simply the framework default disk). `store()` uses `putFile($directory, $file)` and
returns the generated key; `url()` short-circuits empty keys before delegating to the
disk's `url()`; `delete()` treats a missing object as success.

The service is bound as a singleton in `AppServiceProvider` and injected into the
controllers.

### 3. Upload_Handler changes

| Handler | Current behavior | Change |
| --- | --- | --- |
| `ClientApplicationController::store` | `->store("applications/{id}", 'public')` inside a `foreach` over product documents | Wrap application + document creation in a DB transaction; store each provided file via `FileStorage::store($file, "applications/{$application->id}")`; on any store exception, roll back the transaction and delete any objects already written this request, then redirect back with an error (Requirements 3.1–3.7, 10.2, 10.4). Optional docs with no file persist an empty `file_path` without calling the store (Req 3.4). |
| `ClientDocumentController::store` | deletes prior via `Storage::disk('public')->delete(...)`, then `->store('application-documents','public')` | Store new file first via `FileStorage::store(...)` inside try/catch; on failure return error and leave record + prior file untouched (Req 4.5, 10.4). On success, attempt `FileStorage::delete($priorKey)` (swallowing errors → Req 4.6; missing object is success → Req 4.7), then persist the new key (Req 4.1–4.4). |
| `ClientCheckoutController::store` | `->store("payments/{id}", 'public')`; validates `max:5120` | Validate `payment_proof` with `max:10240` (Discrepancy 2) and required/mimes; store via `FileStorage::store($file, "payments/{$application->id}")` in try/catch; on failure return error and do **not** mutate `metadata`/`status` (Req 5.1–5.5, 10.2, 10.4). |
| `PaymentMethodResource` `FileUpload::make('icon')` | `->image()->directory('payment-methods')` on default disk | Add `->disk('s3')` (explicit) and keep `->image()` validation. With disk `throw => true`, a storage failure surfaces to the admin; no icon → empty `file_path` (Req 6.1–6.6). Filament does not retry (Req 6.4). |

### 4. URL_Resolver changes (display points)

| Display point | Current | Change |
| --- | --- | --- |
| `ApplicationResource` document `Placeholder` | `\Illuminate\Support\Facades\Storage::url($record->file_path)` | Route through `FileStorage::url($record->file_path)` (default disk `s3`), so empty keys render "No file uploaded" and non-empty keys resolve to MinIO (Req 7.1, 7.2, 7.5). |
| `checkout.blade.php` icon | `Storage::url($paymentMethod->icon)` | Resolve via `FileStorage::url(...)` (or `Storage::disk('s3')->url(...)`) (Req 7.1, 7.3). |
| `PaymentMethodResource` table `ImageColumn::make('icon')` | implicit default disk | Add `->disk('s3')` so the admin table thumbnail resolves from MinIO (Req 8.4). |

### 5. `App\Console\Commands\MigrateLegacyFiles` — Artisan `storage:migrate-legacy`

Copies every file on the local `public` disk into the bucket under an identical key.

```text
signature: storage:migrate-legacy {--dry-run}
algorithm:
  successCount = failureCount = 0
  for each key in Storage::disk('public')->allFiles():           // recursive
      try:
          if targetExists(key) and contentIdentical(public, s3, key):
              continue                                            // skip (Req 9.2)
          stream = Storage::disk('public')->readStream(key)
          Storage::disk('s3')->writeStream(key, stream)           // identical key (Req 9.1)
          successCount++
      catch (Throwable e):
          try: log failing key + reason                           // Req 9.3
          catch (Throwable): // do nothing                        // Req 9.4
          failureCount++
      // source file is never deleted/modified                    // Req 9.5
  report successCount, failureCount                               // Req 9.6
```

- **Idempotency / "content identical"** (Req 9.2): when the target object exists, compare
  content by size first, then by MD5. MinIO returns the MD5 as the object ETag for
  non-multipart uploads; the command compares the source MD5 (`md5_file`/stream hash)
  against the target ETag/checksum and only re-transfers on mismatch. Equal content →
  skip and leave the existing object byte-for-byte unchanged, so two runs yield the same
  bucket state as one.
- **Continue-on-failure** (Req 9.3, 9.4): the per-file `try/catch` (with an inner guarded
  log) guarantees the loop never aborts.
- **Source retention** (Req 9.5): the command only reads from `public`; it never deletes.
- `--dry-run` lists what would transfer without writing (operational convenience).

## Data Models

No migrations are required. The relevant persisted fields already hold disk-relative
object keys (File_References):

| Model / store | Field | Meaning | Example value |
| --- | --- | --- | --- |
| `ApplicationDocument` | `file_path` (string, nullable/empty) | object key of an application document; empty for optional/not-yet-uploaded | `applications/5/passport.pdf` |
| `Application` | `metadata['payment_proof_path']` (JSON) | object key of the manual payment proof | `payments/5/proof.jpg` |
| `PaymentMethod` | `icon` (string, nullable) | object key of the payment-method icon | `payment-methods/bca.png` |

Invariants relied upon by the design:
- A File_Reference is a **disk-relative key** with no leading slash and no disk/bucket
  prefix. The same key is valid on the legacy `public` disk and on the `s3`/MinIO disk —
  this is what makes Requirement 9.1 (identical keys) and URL resolution disk-agnostic.
- An **empty** File_Reference (`''`/`null`) means "no file"; the URL_Resolver maps it to
  an empty string and never contacts MinIO (Requirement 7.5).

### `.env.example` additions (Requirement 2)

A single labeled section will document the MinIO connection. To satisfy the approved
requirement's `AWS_*` naming **and** the chosen `MINIO_*` source of truth, both groups
are documented, with comments explaining that `AWS_*` are optional overrides and `MINIO_*`
are the canonical values:

```dotenv
# ---------------------------------------------------------------------------
# MinIO / S3-compatible object storage (Storage_Disk "s3", bucket: alliago)
# ---------------------------------------------------------------------------
FILESYSTEM_DISK=s3                     # use the MinIO disk as the application default

# Canonical MinIO connection (source of truth for the s3 disk)
MINIO_ENDPOINT=                        # host:port WITHOUT scheme, e.g. 194.233.91.132:19000
MINIO_ACCESS_KEY=                      # MinIO access key
MINIO_SECRET_KEY=                      # MinIO secret key
MINIO_BUCKET_NAME=alliago              # single bucket holding all uploaded files
MINIO_SECURE=false                     # false => http endpoint, true => https endpoint

# Optional AWS_* overrides (take precedence over MINIO_* when set)
AWS_ENDPOINT=                          # full endpoint URL incl. scheme; overrides MINIO_ENDPOINT/MINIO_SECURE
AWS_URL=                               # public base URL for object access, e.g. {endpoint}/alliago
AWS_BUCKET=alliago                     # overrides MINIO_BUCKET_NAME
AWS_USE_PATH_STYLE_ENDPOINT=true       # MinIO requires path-style addressing
```

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid
executions of a system — essentially, a formal statement about what the system should do.
Properties serve as the bridge between human-readable specifications and
machine-verifiable correctness guarantees.*

Most of this feature is configuration, Filament wiring, and CRUD-style upload handling,
which is best covered by example, edge-case, smoke, and integration tests (see Testing
Strategy). The properties below capture the parts that have genuine universal behavior:
endpoint/URL derivation, key namespacing, validation, delete/transfer idempotency, and
failure-state preservation. Each is implemented with a single property-based test running
≥100 iterations.

### Property 1: MinIO endpoint derivation

*For any* host:port string and any boolean `MINIO_SECURE` value, when `AWS_ENDPOINT` is
absent the derived disk endpoint SHALL equal `("https" if secure else "http") + "://" + host:port`;
and *for any* non-empty `AWS_ENDPOINT` value, the derived endpoint SHALL equal that
override exactly.

**Validates: Requirements 1.3, 1.6**

### Property 2: URL resolution targets the MinIO public base

*For any* non-empty File_Reference key, the URL_Resolver SHALL return a URL whose base
equals the configured MinIO public base (the `AWS_URL` value when set, otherwise the
derived `endpoint + "/" + bucket`) and whose path contains the key, producing it without
contacting the MinIO_Store and never producing a URL whose base is the legacy local disk
base.

**Validates: Requirements 1.5, 7.1, 7.4, 8.4, 8.5**

### Property 3: Empty File_Reference resolves to an empty URL

*For any* empty or blank File_Reference (empty string, `null`, or whitespace-only), the
URL_Resolver SHALL return an empty result and SHALL NOT produce any URL targeting the
MinIO_Store.

**Validates: Requirements 7.5**

### Property 4: Stored keys are namespaced by application identifier

*For any* application identifier and any provided upload filename, the object key produced
when storing an application document SHALL begin with `applications/{id}/`, and the key
produced when storing a payment proof SHALL begin with `payments/{id}/`.

**Validates: Requirements 3.3, 5.2**

### Property 5: Upload validation rejects unsupported files without storing

*For any* candidate upload, the Upload_Handler SHALL accept it only when it satisfies the
field's constraints (application/replacement documents: type in {pdf, jpg, jpeg, png} and
size ≤ 5120 KB; payment proof: present and size ≤ 10,485,760 bytes; payment-method icon:
a valid image), and *for any* candidate violating those constraints the handler SHALL
reject the submission with a validation error and write nothing to the Storage_Bucket.

**Validates: Requirements 3.5, 3.7, 5.4, 6.5**

### Property 6: Delete is idempotent

*For any* object key and any disk state (object present or absent), `FileStorage::delete`
SHALL report success and the key SHALL be absent afterwards, so deleting a key that no
longer exists is treated as a successful deletion.

**Validates: Requirements 4.7**

### Property 7: Legacy transfer fidelity and source retention

*For any* set of files on the local `public` disk, after the transfer command runs each
file SHALL exist in the Storage_Bucket under its identical object key with identical
content, and each source file SHALL remain present and unchanged on the local `public`
disk.

**Validates: Requirements 9.1, 9.5**

### Property 8: Legacy transfer is idempotent

*For any* set of files on the local `public` disk, running the transfer command twice
SHALL leave the Storage_Bucket in a state identical (same keys, same content) to running
it once, and any pre-existing bucket object whose content already matches its source
SHALL be left byte-for-byte unchanged.

**Validates: Requirements 9.2**

### Property 9: Failed storage leaves the database unchanged

*For any* application submission in which storing at least one document fails, the
database SHALL contain exactly the records it held before the submission attempt (no
application document rows persisted, no dangling File_Reference), i.e. a failed store is a
transactional no-op on persisted state.

**Validates: Requirements 3.6, 10.2, 10.4**

## Error Handling

The disk is configured with `throw => true`, so Flysystem raises an exception on write or
delete failure instead of returning `false`. Each handler converts that into the
user-facing outcome the requirements demand:

| Failure point | Detection | Handler response | State guarantee |
| --- | --- | --- | --- |
| Application document store (`ClientApplicationController`) | exception from `FileStorage::store` inside a DB transaction | roll back transaction; delete any objects written earlier in this request; redirect back with a validation/error message | no application-document rows persisted; no dangling key (Req 3.6, 10.2, 10.4) |
| Replacement store (`ClientDocumentController`) | exception from `FileStorage::store` | return error response; do not touch the record | prior `file_path` and prior object retained (Req 4.5) |
| Prior-file delete after successful replacement store | exception from `FileStorage::delete` | swallow (log debug); proceed to persist new key | replacement completes; no error to client (Req 4.6) |
| Prior-file delete, object already gone | `delete` treats missing as success | proceed to persist new key | (Req 4.7) |
| Payment-proof store (`ClientCheckoutController`) | exception from `FileStorage::store` | return error; require resubmit | `metadata`/`status` unchanged, no proof key persisted (Req 5.5, 10.2, 10.4) |
| Icon store (`PaymentMethodResource`) | Filament surfaces disk exception | error notification to admin; no retry | no icon key persisted (Req 6.4) |
| Validation failures (type/size/missing/non-image) | Laravel validation / Filament `->image()` before store | 422 / validation error | nothing written to bucket (Req 3.7, 5.4, 6.5) |
| Store timeout (no confirmation within 30 s) | S3 client `connect_timeout`/`timeout` (≤30 s) raises | treated as a store failure → above paths | (Req 10.1, 10.3) |
| Legacy transfer per-file failure | per-file `try/catch`; inner guarded `try/catch` around logging | record failing key, increment failure count, continue | loop never aborts; source untouched (Req 9.3, 9.4, 9.5) |

Timeout note (Req 10.1/10.3): the 30 s bound is enforced at the S3 client layer via the
disk `options.http.timeout`. When the store does not confirm within that window the SDK
throws, which the handlers treat as a failure and return an error to the user — keeping
the end-to-end failure response within the 30 s budget.

## Testing Strategy

Property-based testing applies to the derivation/idempotency/validation logic above but
NOT to the configuration, Filament rendering, or simple persistence wiring — those use
smoke, example, integration, and snapshot-style tests. The two layers are complementary.

### Tooling

- **Framework:** PHPUnit 11 (already in `composer.json`, `phpunit.xml` present). Tests run
  via `php artisan test` (the `composer test` script) or
  `vendor/bin/phpunit`.
- **Storage fakes:** `Storage::fake('s3')` and `Storage::fake('public')` for handler and
  transfer tests, so no live MinIO is required for the bulk of the suite.
- **Property-based testing library:** a PHP PBT library will be added as a dev dependency
  rather than hand-rolling generators. Preferred: **`innmind/black-box`** (actively
  maintained, expressive generators, PHPUnit-compatible). Acceptable alternative:
  **`giorgiosironi/eris`**. The chosen library MUST be used; do not implement PBT from
  scratch. Each property test runs a **minimum of 100 iterations**.
- **File generation:** uploads simulated with `Illuminate\Http\UploadedFile::fake()`
  (`->create(name, sizeKb, mime)` and `->image(name)`), with generators choosing
  type/size/name to drive Properties 4 and 5.

### Property test mapping and tags

Each correctness property is implemented by exactly one property-based test, tagged with a
comment in this format:

`Feature: minio-file-storage-migration, Property {n}: {property text}`

| Property | Test (suite) | Generated inputs |
| --- | --- | --- |
| P1 endpoint derivation | `Unit/MinioConfigPropertyTest` | host:port strings, `MINIO_SECURE` bool, optional `AWS_ENDPOINT` override |
| P2 URL resolution base + contains key | `Unit/FileStorageUrlPropertyTest` | arbitrary valid object keys; configured public base |
| P3 empty key → empty URL | `Unit/FileStorageUrlPropertyTest` | empty/`null`/whitespace inputs |
| P4 key namespacing | `Feature/UploadNamespacingPropertyTest` | application ids, filenames/extensions |
| P5 upload validation rejection | `Feature/UploadValidationPropertyTest` | mixes of valid/invalid type, size around boundaries, image/non-image |
| P6 delete idempotency | `Unit/FileStorageDeletePropertyTest` | keys + present/absent disk state |
| P7 transfer fidelity + source retention | `Feature/LegacyTransferPropertyTest` | random {key, content} sets on fake `public` |
| P8 transfer idempotence | `Feature/LegacyTransferPropertyTest` | random {key, content} sets; double-run |
| P9 failed store → DB unchanged | `Feature/ApplicationStoreFailurePropertyTest` | document sets with an injected failing store |

### Example, edge-case, smoke, and integration tests

- **Smoke (single execution):** `s3` disk uses driver `s3` (1.1), bucket resolves to
  `alliago` (1.2), path-style defaults to `true` (1.4), `.env.example` contains the
  labeled MinIO section with `AWS_ENDPOINT`, `AWS_URL`, `AWS_BUCKET=alliago`,
  `AWS_USE_PATH_STYLE_ENDPOINT=true`, `FILESYSTEM_DISK=s3` (2.1–2.6), and the s3 client is
  configured with a 30 s timeout (10.1).
- **Example (feature tests, `Storage::fake('s3')` + `Storage::fake('public')`):**
  application document store + persist (3.1, 3.2), optional doc no-file (3.4), submission
  rejected on store failure (3.6); replacement store/persist/order (4.1–4.4), replacement
  store-failure (4.5), prior-delete-failure tolerated (4.6); proof store/persist (5.1,
  5.3), proof store-failure (5.5); icon store/prefix/persist (6.1–6.3), icon store-failure
  no-retry (6.4), no-icon empty ref (6.6); admin link renders resolver URL (7.2), checkout
  img renders resolver URL (7.3); nothing written to local disk after each handler
  (8.1–8.3); transfer failure recording + continuation + counts (9.3, 9.4, 9.6); error
  returned to user on failure (10.2, 10.3).
- **Integration (1–2 examples, optional, against a real/dev MinIO):** end-to-end store →
  URL fetch round trip, and a stalled-endpoint timeout check (10.1). These are gated so
  the default suite stays hermetic with fakes.

### Unit vs property balance

Property tests carry the input-space coverage (derivation, validation boundaries,
idempotency, transfer over arbitrary file sets). Unit/feature examples pin down the
concrete wiring and the specific failure branches that are about control flow rather than
universal behavior. This keeps the example count focused and avoids duplicating what the
generators already cover.

## Requirements Follow-ups (for review)

These items diverge from the approved `requirements.md` and should be confirmed or used to
amend the requirements:

1. **`MINIO_*` vs `AWS_*` naming (Req 1.3/1.5/1.6, Req 2, Req 7.1).** This design treats
   `MINIO_*` as the source of truth with `AWS_*` as optional overrides. If the
   requirements should instead mandate `AWS_*` only, the config and `.env.example` would
   change accordingly.
2. **Payment-proof size limit (Req 5.1/5.4).** Requirements say 10 MB; current code uses
   5 MB. This design raises the limit to 10 MB (`max:10240`) and updates the blade helper
   text. Confirm 10 MB is intended.
3. **Path-style default (Req 1.4).** Requirements say default disabled; MinIO requires
   path-style, so this design defaults it to `true`. Confirm the MinIO-oriented default.
