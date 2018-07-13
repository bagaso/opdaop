<?php

namespace App\Rules\ManageServers\ServerEdit;

use App\Server;
use Illuminate\Contracts\Validation\Rule;

class DownloadSpeedCheckOnlineUserRule implements Rule
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
        $server = Server::findorfail($this->server_id);
        if(($server->dl_speed_openvpn == $value) || $server->dl_speed_openvpn <> $value && $server->online_users->count() == 0) {
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
        return 'Users are currently online on the Server.';
    }
}
