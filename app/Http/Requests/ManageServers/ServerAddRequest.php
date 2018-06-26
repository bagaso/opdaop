<?php

namespace App\Http\Requests\ManageServers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServerAddRequest extends FormRequest
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
        return [
            'server_type' => [
                'bail',
                'required',
                'string',
                Rule::in(['VPN', 'SS', 'SSH']),
            ],
            'server_name' => [
                'bail',
                'required',
                Rule::unique('servers'),
            ],
            'server_ip' => [
                'bail',
                'required',
                'ipv4',
                Rule::unique('servers'),
            ],
            'sub_domain' => [
                'bail',
                'required',
                Rule::unique('servers'),
            ],
            'server_key' => [
                'bail',
                'required',
                Rule::unique('servers'),
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
        ];
    }
}
