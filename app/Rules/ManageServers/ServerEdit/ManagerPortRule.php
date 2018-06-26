<?php

namespace App\Rules\ManageServers\ServerEdit;

use Illuminate\Contracts\Validation\Rule;

class ManagerPortRule implements Rule
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
        //
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Users are currently online on the Server.';
    }
}
