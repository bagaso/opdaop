<?php

namespace App\Http\Requests\ManageServers;

use App\Rules\ManageServers\ServerEdit\PrivateUserCheckIfExistsRule;
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
        return true;
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
                'required',
                Rule::exists('users'),
                new PrivateUserCheckIfExistsRule($this->id),
            ]
        ];
    }
}
