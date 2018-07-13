<?php

namespace App\Http\Requests\ManageUsers;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserFreezeRequest extends FormRequest
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
            # Disallow if user is 'Expired'
            if($user->expired_at == 'Expired') {
                return false;
            }
            # Disallow if account has no permission to bypass USER_BYPASS_FREEZE_LIMIT and user is no freeze left
            if($user->freeze_ctr < 1 && auth()->user()->cannot('BYPASS_USER_FREEZE_LIMIT_ID', $user->id)) {
                return false;
            }
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
