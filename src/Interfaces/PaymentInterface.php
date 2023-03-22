<?php

namespace Digitwires\Payments\Interfaces;

use http\Client\Request;

interface PaymentInterface
{
    public function pay(
        $amount,
        $user_id = null,
        $user_first_name = null,
        $user_last_name = null,
        $user_email = null,
        $user_phone = null,
        $source = null,
        $currency = null
    );

    public function verifyPayment(Request $request);
}
