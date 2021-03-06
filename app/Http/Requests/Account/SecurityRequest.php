<?php

namespace App\Http\Requests\Account;

use App\Rules\Account\Security\OldPasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class SecurityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('UPDATE_ACCOUNT')) {
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
            'password' => ['required', new OldPasswordRule()],
            'new_password' => 'bail|required|between:6,30|confirmed',
            'new_password_confirmation' => 'bail|required|between:6,30',
        ];
    }
}
