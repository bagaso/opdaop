<?php

namespace App\Rules\ManageUsers\DeleteUser;

use App\OnlineUser;
use Illuminate\Contracts\Validation\Rule;

class DeleteUsers implements Rule
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
        foreach($value as $user_id) {
            if(auth()->user()->cannot('DELETE_USER_ID', $user_id)) {
                return false;
            }
            $online_user = OnlineUser::where('user_id', $user_id);
            if($online_user->count() > 0) {
                return false;
            }
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
        return 'The user can\'t be deleted.';
    }
}
