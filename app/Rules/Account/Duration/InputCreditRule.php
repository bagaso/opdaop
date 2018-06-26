<?php

namespace App\Rules\Account\Duration;

use Illuminate\Contracts\Validation\Rule;

class InputCreditRule implements Rule
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
        if(auth()->user()->isAdmin() || auth()->user()->freeze_mode) {
            return false;
        }
        if(auth()->user()->can('PCODE_004')) {
            return true;
        }
        return (auth()->user()->getOriginal('credits') - $value) >= 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if(auth()->user()->isAdmin()) {
            return 'Cannot Reload.';
        }
        if(auth()->user()->freeze_mode) {
            return 'Cannot reload while account is freeze mode.';
        }
        return 'Insufficient Credit.';
    }
}
