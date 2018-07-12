<?php

namespace App\Http\Requests\ManageUsers;

use Illuminate\Foundation\Http\FormRequest;

class UserListSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(request()->getUri() === route('manage_users.user_list.all')) {
            if(auth()->user()->can('MANAGE_USER_ALL')) {
                return true;
            }
        }
        if(request()->getUri() === route('manage_users.user_list.sub_admin')) {
            if(auth()->user()->isAdmin()) {
                return true;
            }
        }
        if(request()->getUri() === route('manage_users.user_list.reseller')) {
            if(auth()->user()->can('MANAGE_USER_ALL')) {
                return true;
            }
        }
        if(request()->getUri() === route('manage_users.user_list.sub_reseller')) {
            if(auth()->user()->can('MANAGE_USER_ALL')) {
                return true;
            }
        }
        if(request()->getUri() === route('manage_users.user_list.client')) {
            if(auth()->user()->can('MANAGE_USER_ALL')) {
                return true;
            }
        }
        if(request()->getUri() === route('manage_users.user_list.other')) {
            if(auth()->user()->can('MANAGE_USER_OTHER')) {
                return true;
            }
        }
        if(request()->getUri() === route('manage_users.user_list.trash')) {
            if(auth()->user()->can('MANAGE_USER_ALL')) {
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
