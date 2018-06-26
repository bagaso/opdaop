<?php

namespace App\Http\Requests\Account;

use App\Rules\Account\Profile\UsernameRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
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
            'username' => [
                'bail',
                'required_with:username',
                'alpha_num',
                'between:6,20',
                new UsernameRule(),
                Rule::unique('users')->ignore(auth()->user()->id),
            ],
            'email' => [
                'bail',
                'required',
                'email',
                'max:50',
                Rule::unique('users')->ignore(auth()->user()->id),
            ],
            'fullname' => 'bail|required',
            'contact' => 'required_if:distributor,on',
        ];
    }
}
