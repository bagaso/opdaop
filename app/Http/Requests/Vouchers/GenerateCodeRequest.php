<?php

namespace App\Http\Requests\Vouchers;

use App\Rules\Vouchers\GenerateCode\CreditRule;
use Illuminate\Foundation\Http\FormRequest;

class GenerateCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('MANAGE_VOUCHER')) {
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
            'credit' => [
                'bail',
                'required',
                'integer',
                'min:1',
                'max:5',
                new CreditRule(),
            ]
        ];
    }
}
