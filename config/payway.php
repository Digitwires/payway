<?php

return [
    "APP_NAME" => env("APP_NAME"),

    # PayPal Gateway Config
    "PAYPAL_CLIENT_ID" => env("PAYPAL_CLIENT_ID"),
    "PAYPAL_CLIENT_SECRET" => env("PAYPAL_CLIENT_SECRET"),
    "PAYPAL_MODE" => env("PAYPAL_MODE", "sandbox"),
    "PAYPAL_CURRENCY" => env("PAYPAL_CURRENCY", "USD"),
];
