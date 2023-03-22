<?php

namespace Digitwires\Payway\Classes;

use Digitwires\Payway\Traits\SetVariables;

class BaseController
{
    use SetVariables;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
    }
}
