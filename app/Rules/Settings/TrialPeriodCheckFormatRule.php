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
        if(in_array($value, ['0h','1h','2h','3h','4h','5h','6h','7h','9h','10h','11h','12h','13h','14h','15h','16h','17h','18h','19h','20h','21h','22h','23h','24h','1d','2d','3d','4d','5d'])) {
            return true;
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
        return 'The trial period format in invalid.';
    }
}
