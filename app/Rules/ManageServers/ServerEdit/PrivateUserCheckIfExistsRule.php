<?php

namespace App\Rules\ManageServers\ServerEdit;

use App\Server;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class PrivateUserCheckIfExistsRule implements Rule
{
    private $server_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($server_id)
    {
        $this->server_id = $server_id;
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
        $server = Server::findorfail($this->server_id);

        if(!$server->privateUsers()->where('user_id')->exists()) {
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
        return 'The user is already added in the server.';
    }
}
