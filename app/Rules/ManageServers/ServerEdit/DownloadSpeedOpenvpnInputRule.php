<?php

namespace App\Rules\ManageServers\ServerEdit;

use Illuminate\Contracts\Validation\Rule;

class DownloadSpeedOpenvpnInputRule implements Rule
{
    private $memory_type;

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
        $memory_type = substr($value, -1, 1);
        $this->memory_type = $memory_type;
        $memory_interval = (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        if(in_array($memory_type, ['mbit']) && $memory_interval >= 0 && $memory_interval <= 500) {
            return  true;
        }
        if(in_array($memory_type, ['kbit']) && $memory_interval >= 0 && $memory_interval <= 1024) {
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
        if(in_array($this->memory_type, ['mbit'])) {
            return  'Mbit speed base is between is 0 - 500.';
        }
        if(in_array($this->memory_type, ['kbit'])) {
            return  'Kbit speed base is between 0 - 1024.';
        }
    }
}
