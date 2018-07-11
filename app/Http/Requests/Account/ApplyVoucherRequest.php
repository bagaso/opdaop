<?php

namespace App\Http\Requests\Account;

use App\Rules\Account\Voucher\CheckIfUnlimitedCreditsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplyVoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('APPLY_VOUCHER_TO_ACCOUNT')) {
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
                new CheckIfUnlimitedCreditsRule,
                Rule::exists('vouchers', 'code')->where(function ($query) {
                    $query->whereNull('user_id')->whereNull('updated_at');
                }),
            ]
        ];
    }
}
