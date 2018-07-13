<?php

namespace App\Rules\Settings;

use Illuminate\Contracts\Validation\Rule;

class DataAllowanceInputRule implements Rule
{
    private $data_allowance_type;

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
        $this->data_allowance_type = $data_allowance_type;
        $data_allowance_interval = (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        if(in_array($data_allowance_type, ['mb']) && $data_allowance_interval >= 0 && $data_allowance_interval <= 1024) {
            return  true;
        }
        if(in_array($data_allowance_type, ['gb']) && $data_allowance_interval >= 0 && $data_allowance_interval <= 50) {
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
        if(in_array($this->data_allowance_type, ['mb'])) {
            return  'Megabytes data allowance base must between is 0 - 1024.';
        }
        if(in_array($this->data_allowance_type, ['gb'])) {
            return  'GigaBytes data allowance base must between 0 - 50.';
        }
    }
}
