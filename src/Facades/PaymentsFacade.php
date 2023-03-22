<?php

namespace Digitwires\Payments\Facades;

class DigitwiresPaymentsFacade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return "digitwires-payments";
    }
}
