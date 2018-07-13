<?php

namespace App\Http\Requests\ManageUsers;

use Illuminate\Foundation\Http\FormRequest;

class UserSecurityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('MANAGE_USER_SECURITY_ID', $this->id)) {
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
            'new_password' => 'bail|required|between:6,30|confirmed',
            'new_password_confirmation' => 'bail|required|between:6,30',
        ];
    }
}
