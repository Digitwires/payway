<?php

namespace Digitwires\Payway\Facades;

use Illuminate\Support\Facades\Facade;

class PaywayFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "digitwires-payway";
    }
}
