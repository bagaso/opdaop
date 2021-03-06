<?php

namespace App\Rules\Settings;

use Illuminate\Contracts\Validation\Rule;

class TrialPeriodCheckFormatRule implements Rule
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
        $trial_type = substr($value, -1, 1);
        if(in_array($trial_type, ['h', 'd'])) {
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
        return 'The trial type format is invalid.';
    }
}
