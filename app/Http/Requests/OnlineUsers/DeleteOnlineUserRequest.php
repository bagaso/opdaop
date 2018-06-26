<?php

namespace App\Http\Requests\OnlineUsers;

use Illuminate\Foundation\Http\FormRequest;

class DeleteOnlineUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('DELETE_ONLINE_USER')) {
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
            'ids' => 'bail|required|array'
        ];
    }
}
