<?php

namespace App\Http\Requests\ManageUsers;

use Illuminate\Foundation\Http\FormRequest;

class UserServicePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('UPDATE_USER_SECURITY', $this->id)) {
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
            'service_password' => 'bail|required|between:6,30',
        ];
    }
}
