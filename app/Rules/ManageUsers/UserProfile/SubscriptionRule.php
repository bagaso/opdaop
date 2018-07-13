<?php

namespace App\Rules\ManageUsers\UserProfile;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class SubscriptionRule implements Rule
{
    private $user_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
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
        $user = User::findorfail($this->user_id);
        if(($user->subscription_id !== (int)$value && auth()->user()->can('MANAGE_USER_SUBSCRIPTION_ID', $user->id)) || $user->subscription_id === (int)$value) {
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
        return 'No Permission to Subscription.';
    }
}
