### PayPal Gateway Example

Environment Variables for PayTabs

```
PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=
PAYPAL_MODE=
PAYPAL_CURRENCY=
```

Example of using the package with PayPal

```php
$payway = new PaypalGateway();

// You can use the following method to pay invoices
$paymentRequest = $payway->initPayment([
    'amount' => 100,
    'user_id' => '111',
    'user_first_name' => 'John',
    'user_last_name' => 'Doe',
    'user_email' => 'john@example.com',
    'user_phone' => '+11234567890',
    'source' => 'website',
    'currency' => 'USD',
]);

// You can use the following method to verify payments
$payway->verifyPayment($paymentRequest)
```

### PayTabs Gateway Example

Environment Variables for PayTabs

```
PAYTABS_PROFILE_ID=
PAYTABS_SERVER_KEY=
PAYTABS_BASE_URL=
PAYTABS_CHECKOUT_LANG=
PAYTABS_VERIFY_URL=
PAYTABS_CANCEL_URL=
```

Example of using the package with PayTabs

```php
$payway = new PaytabsGateway();

// You can use the following method to pay invoices
$paymentRequest = $payway->initPayment([
    'amount' => 100,
    'user_id' => '111',
    'user_first_name' => 'John',
    'user_last_name' => 'Doe',
    'user_email' => 'john@example.com',
    'user_phone' => '+11234567890',
    'source' => 'website',
    'currency' => 'USD',
]);

// You can use the following method to verify payments
$payway->verifyPayment($paymentRequest)
```

### Paymob Gateway Example

Environment Variables for Paymob

```
PAYMOB_API_KEY=
PAYMOB_INTEGRATION_ID=
PAYMOB_IFRAME_ID=
PAYMOB_HMAC_SECRET=
PAYMOB_CURRENCY=
```

Example of using the package with Paymob

```php
$payway = new PaymobGateway();

// You can use the following method to pay invoices
$paymentRequest = $payway->initPayment([
    'amount' => 100,
    'user_id' => '111',
    'user_first_name' => 'John',
    'user_last_name' => 'Doe',
    'user_email' => 'john@example.com',
    'user_phone' => '+11234567890',
    'source' => 'website',
    'currency' => 'USD',
]);

// You can use the following method to verify payments
$payway->verifyPayment($paymentRequest)

// You can use the following method to refund payments
$payway->refundPayment(
    transaction_id = $paymentRequest->transaction_id, 
    $amount = 100
)

// You can use the following method to void transactions
$payway->voidTransaction(
    transaction_id = $paymentRequest->transaction_id
)
```