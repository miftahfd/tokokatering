<?php

namespace App\Rules;

use App\Helpers\IrisHelper;
use Illuminate\Contracts\Validation\Rule;

class ValidAccountRule implements Rule
{
    protected $payment_type, $bank_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($payment_type, $bank_id = null)
    {
        $this->payment_type = $payment_type;
        $this->bank_id = $bank_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($this->payment_type == 'iris' && $value) {
            return IrisHelper::accountValidation($value, $this->bank_id);
        } elseif($this->payment_type == 'linkaja') {
            return true;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->payment_type == 'iris') {
            return 'No rekening tidak valid';
        } elseif($this->payment_type == 'linkaja') {
            return 'Akun Linkaja tidak valid';
        }
    }
}
