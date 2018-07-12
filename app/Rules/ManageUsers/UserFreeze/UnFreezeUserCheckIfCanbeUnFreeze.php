<?php

namespace App\Rules\ManageUsers\UserFreeze;

use Illuminate\Contracts\Validation\Rule;

class UnFreezeUserCheckIfCanbeUnFreeze implements Rule
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
        foreach ($value as $user_id) {
            $user = User::findorfail($user_id);
            if(auth()->user()->can('UPDATE_USER_FREEZE', $user->id)) {
                # Disallow if user is 'Expired'
                if($user->expired_at == 'Expired') {
                    return false;
                }
                # Disallow if account has no permission to bypass USER_BYPASS_FREEZE_LIMIT and user is no freeze left
                if($user->freeze_ctr < 1 && auth()->user()->cannot('BYPASS_USER_FREEZE_LIMIT', $user->id)) {
                    return false;
                }
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
