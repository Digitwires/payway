<?php

namespace Digitwires\Payway\Classes;

use Digitwires\Payway\Traits\SetRequiredFields;
use Digitwires\Payway\Traits\SetVariables;

class BaseController
{
    use SetVariables, SetRequiredFields;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
    }
}
