<?php

namespace App\Http\Requests\ManageServers;

use App\Rules\ManageServers\ServerEdit\PrivateUserCanAddUser;
use App\Rules\ManageServers\ServerEdit\PrivateUserCheckIfActiveRule;
use App\Rules\ManageServers\ServerEdit\PrivateUserCheckIfExistsRule;
use App\Rules\ManageServers\ServerEdit\PrivateUserCheckIfSelfAdd;
use App\Rules\ManageServers\ServerEdit\PrivateUserCheckSubscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddPrivateUserRequest extends FormRequest
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
            'username' => [
                'bail',
                'required',
                Rule::exists('users'),
                new PrivateUserCheckIfExistsRule($this->id),
                new PrivateUserCheckIfSelfAdd,
                new PrivateUserCheckSubscription,
                new PrivateUserCheckIfActiveRule,
                new PrivateUserCanAddUser,
            ]
        ];
    }
}
