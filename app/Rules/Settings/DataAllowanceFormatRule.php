<?php

namespace App\Rules\Settings;

use Illuminate\Contracts\Validation\Rule;

class DataAllowanceFormatRule implements Rule
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
        $data_allowance_type = substr($value, -2, 2);
        if(in_array($data_allowance_type, ['mb', 'gb'])) {
            return  true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The data allowance type format in invalid.';
    }
}
