<?php

namespace App\Rules\ManageUsers\UserCredit;

use Illuminate\Contracts\Validation\Rule;

class CreditRule implements Rule
{
    private $account;
    private $user;
    private $input_credits;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($account, $user, $input_credits)
    {
        $this->account = $account;
        $this->user = $user;
        $this->input_credits = $input_credits;
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
        if($this->input_credits == 0) {
            return false;
        }
        if($this->user->credits === 'No Limit') {
            return false;
        }
        if ($this->input_credits < 0 && ($this->user->getOriginal('credits') + $this->input_credits) < 0) {
            return false;
        }
        if($this->account->credits === 'No Limit') {
            return true;
        }
        return ($this->account->getOriginal('credits') - $this->input_credits) >= 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->input_credits == 0) {
            return 'Invalid Input Credit.';
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
