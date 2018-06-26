<?php

namespace App\Rules\Account\TransferCredit;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class CreditRule implements Rule
{
    private $username;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($username)
    {
        $this->username = $username;
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
        $user = User::where('username', $this->username);
        if($user->exists()) {
            $user = $user->first();
            if(in_array($user->group_id, [2]) && $value >= app('settings')->renewal_qualified) {
                return true;
            }
            if(in_array($user->group_id, [3,4]) && $value >= app('settings')->renewal_qualified) {
                return true;
            }
            if(in_array($user->group_id, [5]) && $value >= 1) {
                return true;
            }
            if($user->parent_id !== auth()->user()->id  && auth()->user()->cannot('TRANSFER_CREDIT_OTHER')) {
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $user = User::where('username', $this->username);
        if($user->exists()) {
            $user = $user->first();
            if(in_array($user->group_id, [2])) {
                return 'Mimimum for ' . $this->username . ' is ' . app('settings')->renewal_qualified . ' credits.';
            }
            if(in_array($user->group_id, [3,4])) {
                return 'Mimimum for ' . $this->username . ' is ' . app('settings')->renewal_qualified . ' credits.';
            }
            if(in_array($user->group_id, [5])) {
                return 'Mimimum for ' . $this->username . ' is 1 credit.';
            }
        }
    }
}
