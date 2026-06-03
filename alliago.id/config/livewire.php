<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads
    |--------------------------------------------------------------------------
    |
    | Livewire supports file uploads. For security and compatibility, temporary
    | uploads are stored in a non-public directory on your local filesystem.
    |
    | Setting this to 'local' forces all temporary uploads to go through the
    | local server first, resolving Mixed Content (HTTP/HTTPS) block issues
    | when using S3/MinIO over HTTP.
    |
    */

    'temporary_file_upload' => [
        'disk' => 'local',        // Force local temporary uploads to resolve Mixed Content blocks
        'rules' => null,
        'directory' => null,
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a', 'jpg',
            'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_size' => 12 * 1024, // 12MB
    ],

];
