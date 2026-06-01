<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

/**
 * Central abstraction for persisting and resolving uploaded files against the
 * configured MinIO (S3-compatible) Storage_Disk.
 *
 * Every Upload_Handler and URL_Resolver routes through this contract so the
 * application writes to, deletes from, and resolves URLs against a single disk
 * (never the legacy local disk).
 */
interface FileStorage
{
    /**
     * Store an uploaded file under the given directory and return its object key.
     *
     * The returned value is the disk-relative File_Reference (no leading slash,
     * no bucket prefix) that should be persisted to the database.
     *
     * @param  UploadedFile  $file       The uploaded file to persist.
     * @param  string        $directory  The directory/prefix to namespace the object under.
     * @return string                    The generated object key for the stored file.
     *
     * @throws \Throwable When the file cannot be stored, so callers can avoid
     *                    persisting a dangling File_Reference.
     */
    public function store(UploadedFile $file, string $directory): string;

    /**
     * Delete the object identified by the given key.
     *
     * The operation is idempotent: a missing object (including a null/empty key)
     * is treated as a successful deletion. Returns true when the object is gone
     * afterwards.
     *
     * @param  string|null  $key  The object key to delete, or null/empty for no-op.
     * @return bool               True when the object is absent after the call.
     */
    public function delete(?string $key): bool;

    /**
     * Resolve a public URL for the given object key.
     *
     * An empty, null, or whitespace-only key resolves to an empty string ('')
     * without contacting the Storage_Disk. A non-empty key resolves to a URL
     * whose base is the configured MinIO public base.
     *
     * @param  string|null  $key  The object key to resolve, or null/empty.
     * @return string             The resolved public URL, or '' for an empty key.
     */
    public function url(?string $key): string;

    /**
     * Determine whether an object exists in the Storage_Bucket for the given key.
     *
     * @param  string  $key  The object key to check.
     * @return bool          True when the object exists.
     */
    public function exists(string $key): bool;
}
