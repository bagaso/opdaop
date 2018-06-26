<?php

namespace App\Http\Requests\ManageUsers;

use App\Group;
use App\Status;
use App\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('CREATE_ACCOUNT')) {
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
            'group' => [
                'bail',
                'required',
                'integer',
                Rule::in(json_decode(Group::all()->pluck('id'))),
            ],
            'username' => 'bail|required|alpha_num|between:6,20|unique:users,username',
            'password' => 'bail|required|between:6,30|confirmed',
            'password_confirmation' => 'bail|required|between:6,30',
            'email' => 'bail|required|email|max:50|unique:users,email',
            'fullname' => 'bail|required|max:50',
            'contact' => 'required_if:distributor,on',
            'max_users' => 'bail|required|integer|between:50,1000',
            'status' => [
                'bail',
                'required',
                'integer',
                Rule::in(json_decode(Status::all()->pluck('id'))),
            ],
            'subscription' => [
                'bail',
                'required',
                'integer',
                Rule::in(json_decode(Subscription::all()->pluck('id'))),
            ],
            'subscription' => [
                'bail',
                'required',
                'integer',
                Rule::in(json_decode(Subscription::all()->pluck('id'))),
            ],
        ];
    }
}
