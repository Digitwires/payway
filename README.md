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

## Supported Payment Providers

* [PayPal](#paypal-environment-variables)
* [PayTabs](#paytabs-environment-variables)

## Usage

The following example shows how to use the package with any payment provider.

```php
$payway = new PaypalGateway(); // OR any available payment class

$payway->pay([
    'amount' => 100,
    'user_id' => '111',
    'user_first_name' => 'John',
    'user_last_name' => 'Doe',
    'user_email' => 'john@example.com',
    'user_phone' => '+11234567890',
    'source' => 'website',
    'currency' => 'USD',
]);

// OR
$payment->setAmount(100)
        ->setUserId("111")
        ->setUserFirstName("John")
        ->setUserLastName("Doe")
        ->setUserEmail("john@example.com")
        ->setUserPhone("+11234567890")
        ->setSource("website")
        ->setCurrency("USD")
        ->pay();
```

## Available Providers

```php
use Nafezly\Payments\Classes\PaypalGateway;
use Nafezly\Payments\Classes\PaytabsGateway;
```

### PayPal Environment Variables

```
PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=
PAYPAL_MODE=
PAYPAL_CURRENCY=
```

### PayTabs Environment Variables

```
PAYTABS_PROFILE_ID=
PAYTABS_SERVER_KEY=
PAYTABS_BASE_URL=
PAYTABS_CHECKOUT_LANG=
PAYTABS_VERIFY_URL=
PAYTABS_CANCEL_URL=
```
