<?php

namespace App\Services;

use App\Contracts\FileStorage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Default {@see FileStorage} implementation backed by a single Laravel
 * filesystem disk (the configured MinIO / S3-compatible Storage_Disk).
 *
 * The service centralizes every store, delete, and URL-resolution operation on
 * one disk so the Application never writes to, deletes from, or resolves URLs
 * against the legacy local disk. The disk name defaults to the framework
 * default disk (`config('filesystems.default')`, which is `s3` in deployed
 * environments) but can be overridden via the constructor for testing with
 * {@see Storage::fake()}.
 */
class FileStorageService implements FileStorage
{
    /**
     * The name of the filesystem disk this service operates against.
     */
    protected string $disk;

    /**
     * @param  string|null  $disk  The disk name to use, or null to resolve the
     *                             framework default disk from configuration.
     */
    public function __construct(?string $disk = null)
    {
        $this->disk = $disk ?? (string) config('filesystems.default', 's3');
    }

    /**
     * {@inheritDoc}
     */
    public function store(UploadedFile $file, string $directory): string
    {
        $key = $this->disk()->putFile($directory, $file);

        // With the disk configured as `throw => true`, an underlying failure
        // already raises an exception. Guard the `false` return as well so a
        // failed store never yields a dangling File_Reference to the caller.
        if ($key === false || $key === null || $key === '') {
            throw new RuntimeException(
                "Failed to store uploaded file under [{$directory}] on disk [{$this->disk}]."
            );
        }

        return $key;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(?string $key): bool
    {
        // A null/empty/whitespace-only key is a no-op success.
        if ($key === null || trim($key) === '') {
            return true;
        }

        $disk = $this->disk();

        // A missing object is treated as a successful deletion (idempotent).
        if (! $disk->exists($key)) {
            return true;
        }

        return $disk->delete($key);
    }

    /**
     * {@inheritDoc}
     */
    public function url(?string $key): string
    {
        // Short-circuit empty/blank/whitespace-only keys before touching the
        // disk so callers never produce a URL for an absent File_Reference.
        if ($key === null || trim($key) === '') {
            return '';
        }

        $disk = $this->disk();

        try {
            // For S3/MinIO, if it supports temporaryUrl, we should use it so
            // private buckets work out-of-the-box.
            return $disk->temporaryUrl($key, now()->addMinutes(60));
        } catch (\Throwable $e) {
            // Fallback for local/other disks that do not support temporary URLs
            return $disk->url($key);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function exists(string $key): bool
    {
        return $this->disk()->exists($key);
    }

    /**
     * Resolve the configured Storage_Disk instance.
     */
    protected function disk(): Filesystem
    {
        return Storage::disk($this->disk);
    }
}
