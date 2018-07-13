<?php

namespace App\Http\Requests\ManageUsers;

use Illuminate\Foundation\Http\FormRequest;

class UserDurationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('MANAGE_USER_DURATION_ID', $this->id)) {
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
            'hours' => [
                'bail',
                'required',
                'integer',
                'between:-24,24'
            ],
            'days' => [
                'bail',
                'required',
                'integer',
                'between:-30,30'
            ],
        ];
    }
}
