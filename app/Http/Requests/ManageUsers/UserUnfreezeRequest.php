<?php

namespace App\Http\Requests\ManageUsers;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserUnfreezeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = User::findorfail($this->id);
        if(auth()->user()->can('MANAGE_USER_FREEZE_ID', $user->id)) {
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
