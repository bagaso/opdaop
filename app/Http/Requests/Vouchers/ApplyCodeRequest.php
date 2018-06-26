<?php

namespace App\Http\Requests\Vouchers;

use App\Rules\Vouchers\ApplyCode\CodeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplyCodeRequest extends FormRequest
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
                new CodeRule(),
                Rule::exists('vouchers', 'code')->where(function ($query) {
                    $query->whereNull('user_id')->whereNull('updated_at');
                }),
            ]
        ];
    }
}
