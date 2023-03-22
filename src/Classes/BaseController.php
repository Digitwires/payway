<?php

namespace Digitwires\Payments\Classes;

use Digitwires\Payments\Traits\SetVariables;

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
