<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'h2h' => [
        'base_url' => env('H2H_BASE_URL', 'https://uat-backup.darmawisataindonesiah2h.co.id:7080/h2h/'),
        'login_payload_path' => env('H2H_LOGIN_PAYLOAD_PATH', base_path('script.json')),
        'access_token_cache_key' => env('H2H_ACCESS_TOKEN_CACHE_KEY', 'h2h.access_token'),
        'access_token_ttl_minutes' => (int) env('H2H_ACCESS_TOKEN_TTL_MINUTES', 10),
    ],

];
