# Laravel Payway

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digitwires/payway.svg?style=flat-square)](https://packagist.org/packages/digitwires/payway)
[![Total Downloads](https://img.shields.io/packagist/dt/digitwires/payway.svg?style=flat-square)](https://packagist.org/packages/digitwires/payway)

A simple Laravel implementation for all payment providers.

## Installation

You can install the package via composer:

```bash
composer require digitwires/payway
```

This is the contents of the published config file:

```bash
php artisan vendor:publish --tag="payway-config"
```

This is the contents of the published lang file:

```bash
php artisan vendor:publish --tag="payway-lang"
```

## List of contents

* [Available Providers](#list-of-available-providers)
* [Usage](#usage)
    * [Pay Invoices](#pay-invoices)
    * [Verify Payments](#verify-payments)
* [Change log](#change-log)
* [Contributing](#contributing)
* [Security Vulnerabilities](#security-vulnerabilities)
* [Credits](#credits)
* [License](#license)

## List of available providers

You can navigate to the [Payment Providers Docs](PAYMENTPROVIDERS.md) file to see their examples or click on the links
below.

* [PayPal](PAYMENTPROVIDERS.md#paypal-gateway-example)
* [PayTabs](PAYMENTPROVIDERS.md#paytabs-gateway-example)
* [Paymob](PAYMENTPROVIDERS.md#paymob-gateway-example)

## Usage

### Pay Invoices

The following example shows how to use the package with any payment provider.

```php
$payway = new PaypalGateway(); // OR any available payment class

$payway->initPayment([
    'amount' => 100,
    'user_id' => '111',
    'user_first_name' => 'John',
    'user_last_name' => 'Doe',
    'user_email' => 'john@example.com',
    'user_phone' => '+11234567890',
    'source' => 'website',
    'currency' => 'USD',
]);
```

### Verify Payments

The following example shows how to verify payments with any payment provider.

```php
  $payway = new PaypalGateway(); // OR any available payment class
  
  $payway->verifyPayment($request)
```

### Available Providers

```php
use Digitwires\Payway\Classes\PaypalGateway;
use Digitwires\Payway\Classes\PaytabsGateway;
use Digitwires\Payway\Classes\PaymobGateway;
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

If you discover a security vulnerability within this package, please
email [ahmed_noreldin@outlook.com](mailto:ahmed_noreldin@outlook.com)

## Credits

- [Ahmed M. Ibrahim](https://github.com/2hmad)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.