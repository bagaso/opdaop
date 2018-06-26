<?php

namespace App\Http\Requests\ManageUsers;

use Illuminate\Foundation\Http\FormRequest;

class UserDownlineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('ACCESS_USER_DOWNLINE', $this->id) && auth()->user()->can('MANAGE_USER_OTHER')) {
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
            //
        ];
    }
}
