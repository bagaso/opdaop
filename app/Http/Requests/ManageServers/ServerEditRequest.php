<?php

namespace App\Http\Requests\ManageServers;

use App\Rules\ManageServers\ServerEdit\DataLimitCheckOnlineUserRule;
use App\Rules\ManageServers\ServerEdit\DataLimitRule;
use App\Rules\ManageServers\ServerEdit\DownloadSpeedCheckFormatRule;
use App\Rules\ManageServers\ServerEdit\DownloadSpeedCheckOnlineUserRule;
use App\Rules\ManageServers\ServerEdit\ManagerPortCheckOnlineUserRule;
use App\Rules\ManageServers\ServerEdit\ServerDLSpeedRule;
use App\Rules\ManageServers\ServerEdit\ServerIpCheckOnlineUserRule;
use App\Rules\ManageServers\ServerEdit\ServerIpRule;
use App\Rules\ManageServers\ServerEdit\ServerUPSpeedRule;
use App\Rules\ManageServers\ServerEdit\SubDomainCheckOnlineUserRule;
use App\Rules\ManageServers\ServerEdit\UploadSpeedCheckFormatRule;
use App\Rules\ManageServers\ServerEdit\UploadSpeedCheckOnlineUserRule;
use App\Rules\ManageServers\ServerEdit\WebPortCheckOnlineUserRule;
use App\Rules\ManageServers\ServerEdit\WebPortRule;
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
                new ServerIpCheckOnlineUserRule($this->id),
            ],
            'sub_domain' => [
                'bail',
                'required',
                Rule::unique('servers')->ignore($this->id),
                new SubDomainCheckOnlineUserRule($this->id),
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
                new ManagerPortCheckOnlineUserRule($this->id)
            ],
            'web_port' => [
                'bail',
                'required',
                'integer',
                new WebPortCheckOnlineUserRule($this->id),
            ],
            'download_speed' => [
                'bail',
                'required',
                new DownloadSpeedCheckOnlineUserRule($this->id),
                new DownloadSpeedCheckFormatRule,
            ],
            'upload_speed' => [
                'bail',
                'required',
                new UploadSpeedCheckOnlineUserRule($this->id),
                new UploadSpeedCheckFormatRule,
            ],
            'data_limit' => [
                'bail',
                'required',
                new DataLimitCheckOnlineUserRule($this->id),
            ],
        ];
    }
}
