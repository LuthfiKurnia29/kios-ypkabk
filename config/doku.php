<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DOKU Environment Mode
    |--------------------------------------------------------------------------
    |
    | Supported modes:
    | - 'dummy': Uses the built-in local simulator for development & testing
    | - 'sandbox': Connects to DOKU Sandbox API (https://api-sandbox.doku.com)
    | - 'production': Connects to DOKU Live Production API (https://api.doku.com)
    |
    */
    'environment' => env('DOKU_ENV', 'dummy'),

    /*
    |--------------------------------------------------------------------------
    | DOKU API Credentials
    |--------------------------------------------------------------------------
    |
    | Obtained from DOKU Back Office (Client ID and Shared Secret Key).
    |
    */
    'client_id' => env('DOKU_CLIENT_ID', 'DOKU_MOCK_CLIENT_ID'),
    'secret_key' => env('DOKU_SECRET_KEY', 'DOKU_MOCK_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | DOKU API Base URLs
    |--------------------------------------------------------------------------
    */
    'api_url' => [
        'sandbox' => 'https://api-sandbox.doku.com',
        'production' => 'https://api.doku.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook & Callback Paths
    |--------------------------------------------------------------------------
    */
    'notification_path' => env('DOKU_NOTIFICATION_PATH', '/payment/doku/notify'),
    'return_path' => env('DOKU_RETURN_PATH', '/payment/doku/return'),
];

