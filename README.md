# Malaysia DuitNow Payment

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zarulizham/laravel-duitnow.svg?style=flat-square)](https://packagist.org/packages/zarulizham/laravel-duitnow)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/zarulizham/laravel-duitnow/run-tests?label=tests)](https://github.com/zarulizham/laravel-duitnow/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/zarulizham/laravel-duitnow/Check%20&%20fix%20styling?label=code%20style)](https://github.com/zarulizham/laravel-duitnow/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/zarulizham/laravel-duitnow.svg?style=flat-square)](https://packagist.org/packages/zarulizham/laravel-duitnow)

Package for DuitNow Payment

## Support us

## Installation

You can install the package via composer:

```bash
composer require zarulizham/laravel-duitnow
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="duitnow-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="duitnow-config"
```

You can publish the contorller file with:

```bash
php artisan vendor:publish --tag="duitnow-controller"
```

This is the contents of the published config file:

```php
return [
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
        'uat' => [
            'disk' => 'local',
            'dir' => 'paynet/duitnow/',
        ],
        'production' => [
            'disk' => 'local',
            'dir' => 'paynet/duitnow/',
        ]
    ],

    'direct_path' => env('DUITNOW_DIRECT_PATH'),
    'callback_path' => env('DUITNOW_CALLBACK_PATH'),
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="duitnow-views"
```

## Usage

```php
    $duitNowPayment = new DuitNowPayment();

    $bankInfo = explode('|', $request->bank);
    $bankId = $bankInfo[0];
    $bankType = $bankInfo[1];

    $redirectUrl = $duitNowPayment->initiatePayment(10, "Zarul Zubir", $bankType, "Ref: " . rand(100, 200), $bankId, 'A1000001');
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Zarul Zubir](https://github.com/zarulizham)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
