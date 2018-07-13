<?php

namespace App\Rules\ManageServers\ServerEdit;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class PrivateUserCanAddUser implements Rule
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
        $user = User::where('username', $value)->first();

        if(auth()->user()->can('PRIVATE_SERVER_USER_ADD_REMOVE_ID', $user->id)) {
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
        return 'User can\'t be added.';
    }
}
