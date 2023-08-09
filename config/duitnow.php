<?php
// config for ZarulIzham/DuitNowPayment
return [
    'token_url' => env('DUITNOW_TOKEN_URL'),
    'url' => env('DUITNOW_URL'),
    'client_id' => env('DUITNOW_CLIENT_ID'),
    'product_id' => env('DUITNOW_PRODUCT_ID'),
    'client_secret' => env('DUITNOW_CLIENT_SECRET'),
    'merchant_id' => env('DUITNOW_MERCHANT_ID'),
    'x_signature_key' => env('DUITNOW_X_SIGNATURE_KEY'),
    'token_expiry' => env('DUITNOW_TOKEN_EXPIRY', 3600),
    'bank_cache' => env('DUITNOW_BANK_CACHE', 43200), # 12 hours
    'merchant_name' => env('DUITNOW_MERCHANT_NAME'),
    'account_type' => env('DUITNOW_MERCHANT_ACCOUNT_TYPE'),

    'source_of_funds' => [
        "01"
    ],

    'certificates' => [
        'disk' => 'local',
        'dir' => 'paynet/duitnow/',
    ],

    'direct_path' => env('DUITNOW_DIRECT_PATH'),
    'callback_path' => env('DUITNOW_CALLBACK_PATH'),
];
