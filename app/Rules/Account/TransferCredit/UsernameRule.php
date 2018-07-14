<?php

namespace App\Rules\Account\TransferCredit;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class UsernameRule implements Rule
{
    private $username;
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
        if(auth()->user()->isAdmin()) {
            return true;
        }
        $this->username = strtolower($value);
        $user = User::where('username', $this->username)->firstorfail();
        if($user->parent_id !== auth()->user()->id && auth()->user()->can('TRANSFER_USER_CREDIT_ID', $user->id)) {
            return true;
        }
//        if(!auth()->user()->isAdmin() && $user->parent_id !== auth()->user()->id && auth()->user()->can('TRANSFER_CREDIT_OTHER')) {
//            return true;
//        }
//        if(!auth()->user()->isAdmin() && $user->parent_id === auth()->user()->id && auth()->user()->can('TRANSFER_CREDIT_DOWNLINE')) {
//            return true;
//        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Cannot transfer credits to '. $this->username .'.';
    }
}
