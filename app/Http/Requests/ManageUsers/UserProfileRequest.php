<?php

namespace App\Http\Requests\ManageUsers;

use App\Group;
use App\Rules\ManageUsers\UserProfile\GroupRule;
use App\Rules\ManageUsers\UserProfile\SubscriptionRule;
use App\Rules\ManageUsers\UserProfile\UsernameRule;
use App\Status;
use App\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('MANAGE_USER_PROFILE_ID', $this->id)) {
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
                'sometimes',
                'bail',
                //'required_with:group',
                'integer',
                new GroupRule($this->id),
                Rule::in(json_decode(Group::all()->pluck('id'))),
            ],
            'username' => [
                'sometimes',
                'bail',
                //'required_with:username',
                'alpha_num',
                'between:6,20',
                new UsernameRule($this->id),
                Rule::unique('users')->ignore($this->id),
            ],
            'email' => [
                'bail',
                'required',
                'email',
                'max:50',
                Rule::unique('users')->ignore($this->id),
            ],
            'fullname' => 'bail|required|max:50',
            'contact' => 'required_if:distributor,on',
            'max_users' => 'bail|integer|between:50,1000',
            'status' => [
                'bail',
                'required',
                'integer',
                Rule::in(json_decode(Status::all()->pluck('id'))),
            ],
            'subscription' => [
                'bail',
                'required_with:subscription',
                'integer',
                new SubscriptionRule($this->id),
                Rule::in(json_decode(Subscription::all()->pluck('id'))),
            ],
        ];
    }
}
