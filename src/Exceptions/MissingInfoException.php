<?php

namespace Digitwires\Payments\Exceptions;

class MissingInfoException extends \Exception
{
    public function __construct($missing_parameter = "", $payment_provider = "")
    {
        parent::__construct(
            "Missing parameter: $missing_parameter for $payment_provider"
        );
    }
}
