<?php

namespace App\Http\Requests\ManageServers;

use Illuminate\Foundation\Http\FormRequest;

class RemovePrivateUserRequest extends FormRequest
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
            'user_ids' => [
                'bail',
                'required',
                'array',
            ]
        ];
    }
}
