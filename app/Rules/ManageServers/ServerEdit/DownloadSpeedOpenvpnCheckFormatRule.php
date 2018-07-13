<?php

namespace App\Rules\ManageServers\ServerEdit;

use Illuminate\Contracts\Validation\Rule;

class DownloadSpeedOpenvpnCheckFormatRule implements Rule
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
        $memory_type = substr($value, -4, 4);
        if(in_array($memory_type, ['mbit', 'kbit'])) {
            return  true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The download speed format is invalid.';
    }
}
