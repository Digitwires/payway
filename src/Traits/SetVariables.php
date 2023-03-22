<?php

namespace Digitwires\Payway\Traits;

trait SetVariables
{
    public $user_id;
    public $user_first_name;
    public $user_last_name;
    public $user_email;
    public $user_phone;
    public $source;
    public $currency;
    public $amount;

    /**
     * @param string $value
     * @return $this
     */
    public function setUserId($value)
    {
        $this->user_id = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setUserFirstName($value)
    {
        $this->user_first_name = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setUserLastName($value)
    {
        $this->user_last_name = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setUserEmail($value)
    {
        $this->user_email = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setUserPhone($value)
    {
        $this->user_phone = $value;
        return $this;
    }

    /**
     * Set source
     * @param string $value
     * @return $this
     */
    public function setSource($value)
    {
        $this->source = $value;
        return $this;
    }

    /**
     * Set currency
     * @param string $value
     * @return $this
     */
    public function setCurrency($value)
    {
        $this->currency = $value;
        return $this;
    }

    /**
     * Set amount
     * @param double $value
     * @return $this
     */
    public function setAmount($value)
    {
        $this->amount = $value;
        return $this;
    }

    /**
     * Set all variables in global
     * @param array $value
     * @return void
     */
    public function setVariablesInGlobal(array $value): void
    {
        foreach ($value as $key => $val) {
            $this->$key = $val;
        }
    }
}
