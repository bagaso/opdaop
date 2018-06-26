<?php

namespace App\Http\Requests\ManageUsers;

use Illuminate\Foundation\Http\FormRequest;

class UserUploadPhotoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('UPDATE_USER_PROFILE', $this->id)) {
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
            'file' => [
                'required',
                'image',
                'mimes:jpeg,png',
                'max:2048',
            ]
        ];
    }
}
