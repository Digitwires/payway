<?php

return [
    "APP_NAME" => env("APP_NAME"),

    # PayPal Gateway Config
    "PAYPAL_CLIENT_ID" => "YOUR_CLIENT_ID",
    "PAYPAL_CLIENT_SECRET" => "YOUR_SECRET",
    "PAYPAL_MODE" => "sandbox", // sandbox or live
    "PAYPAL_CURRENCY" => "USD",
    "PAYPAL_VERIFY_URL" => "https://ipnpb.paypal.com/cgi-bin/webscr",
    "PAYPAL_CANCEL_URL" => "http://localhost:8000/cancel",
];
