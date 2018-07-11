<?php

namespace App\Http\Requests\ManageUsers;

use App\Rules\ManageUsers\DeleteUser\DeleteUsers;
use App\Rules\ManageUsers\DeleteUser\UserIdRule;
use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        foreach($this->user_ids as $user_id) {
            if(auth()->user()->cannot('DELETE_USER', $user_id)) {
                return false;
            }
        }
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
            'user_ids' => [
                'bail',
                'required',
                'array',
                new DeleteUsers
            ],
        ];
    }
}
