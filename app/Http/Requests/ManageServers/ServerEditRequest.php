<?php

namespace App\Http\Requests\ManageServers;

use App\Rules\ManageServers\ServerEdit\DataLimitRule;
use App\Rules\ManageServers\ServerEdit\ServerIpRule;
use App\Server;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServerEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('MANAGE_SERVER')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $server = Server::findorfail($this->id);
        return [
            'server_type' => [
                'bail',
                'required',
                'string',
                Rule::in(['openvpn', 'ssh', 'softether', 'ss']),
            ],
            'server_name' => [
                'bail',
                'required',
                Rule::unique('servers')->ignore($this->id),
            ],
            'server_ip' => [
                'bail',
                'required',
                'ipv4',
                Rule::unique('servers')->ignore($this->id),
                new ServerIpRule($this->id),
            ],
            'sub_domain' => [
                'bail',
                'required',
                Rule::unique('servers')->ignore($this->id),
            ],
            'server_key' => [
                'bail',
                'required',
                Rule::unique('servers')->ignore($this->id),
            ],
            'manager_password' => [
                'bail',
                'required',
            ],
            'manager_port' => [
                'bail',
                'required',
                'integer',
            ],
            'web_port' => [
                'bail',
                'required',
                'integer',
            ],
            'download_speed' => [
                'bail',
                'required',
            ],
            'upload_speed' => [
                'bail',
                'required',
            ],
            'data_limit' => [
                'bail',
                'required',
                new DataLimitRule($this->id),
            ],
        ];
    }
}
