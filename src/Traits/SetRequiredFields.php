<?php

namespace Digitwires\Payway\Traits;

use Digitwires\Payway\Exceptions\MissingInfoException;

trait SetRequiredFields
{
    /**
     * @param array $required_field
     * @param string $gateway_name
     * @throws MissingInfoException
     */
    public function checkRequiredFields(
        array $required_field,
        string $gateway_name
    ): void {
        $missing = [];
        foreach ($required_field as $field) {
            if (empty($this->$field)) {
                $missing[] = $field;
            }
        }
        if (count($missing) > 0) {
            throw new MissingInfoException($gateway_name, $missing);
        }
    }
}
