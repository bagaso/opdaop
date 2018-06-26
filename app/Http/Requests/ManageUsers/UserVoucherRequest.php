<?php

namespace App\Http\Requests\ManageUsers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserVoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('UPDATE_USER_VOUCHER', $this->id)) {
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
            'voucher' => [
                'bail',
                'required',
                Rule::exists('vouchers', 'code')->where(function ($query) {
                    $query->whereNull('user_id')->whereNull('updated_at');
                }),
                //new ApplyCheckPermission($this->id)
            ]
        ];
    }
}
