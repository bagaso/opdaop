<?php

namespace App\Http\Requests\OnlineUsers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SearchOnlineUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::check()) {
            if(app('settings')->enable_online_users) {
                return true;
            }
        } else {
            if(app('settings')->enable_online_users && app('settings')->public_online_users) {
                return true;
            }
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
