<?php

namespace App\Rules\Vouchers\GenerateCode;

use Illuminate\Contracts\Validation\Rule;

class CreditRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return auth()->user()->can('PCODE_004') || (auth()->user()->getOriginal('credits') - $value) >= 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Insufficient Credit.';
    }
}
