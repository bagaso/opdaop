<?php

namespace App\Rules\Settings;

use Illuminate\Contracts\Validation\Rule;

class TrialPeriodInputRule implements Rule
{
    private $trial_type;

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
        $this->trial_type = $trial_type;
        $trial_interval = (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        if(in_array($trial_type, ['h']) && $trial_interval >= 0 && $trial_interval <= 24) {
            return  true;
        }
        if(in_array($trial_type, ['d']) && $trial_interval >= 0 && $trial_interval <= 30) {
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
        if(in_array($this->trial_type, ['h'])) {
            return  'Hours trial base is between is 0 - 24.';
        }
        if(in_array($this->trial_type, ['d'])) {
            return  'Days trial base is between 0 - 30.';
        }
    }
}
