<?php

namespace App\Rules\ManageUsers\UserCredit;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class CreditRule implements Rule
{
    private $user;
    private $input_credits;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user = User::findorfail($user_id);
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
        $this->input_credits = $value;
        if($this->input_credits == 0) {
            return false;
        }
        if($this->user->credits === 'No Limit') {
            return false;
        }
        if ($this->input_credits < 0 && ($this->user->getOriginal('credits') + $this->input_credits) < 0) {
            return false;
        }
        if(auth()->user()->credits === 'No Limit') {
            return true;
        }
        return (auth()->user()->getOriginal('credits') - $this->input_credits) >= 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->input_credits == 0) {
            return 'Invalid input credits.';
        }
        if($this->user->credits === 'No Limit') {
            return 'User is not allowed to received Credit.';
        }
        if ($this->input_credits < 0 && ($this->user->getOriginal('credits') + $this->input_credits) < 0) {
            return 'User Credit must be a non-negative.';
        }
        return 'Insufficient Credit.';
    }
}
