<?php

namespace Digitwires\Payway\Interfaces;

use http\Client\Request;

interface PaywayInterface
{
    public function initPayment(
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
