<?php

namespace App\Http\Requests\Account;

use App\Rules\Account\TransferCredit\CreditRule;
use App\Rules\Account\TransferCredit\UsernameRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransferCreditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('TRANSFER_CREDIT')) {
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
            'username' => [
                'bail',
                'required',
                'alpha_num',
                Rule::exists('users')->where(function ($query) {
                    $query->where('id', '<>', 1);
                    $query->where('username', $this->username);
                    if(!auth()->user()->isAdmin()) {
                        $query->where('status_id', 2);
                    }
                }),
                new UsernameRule,
            ],
            'credits' => [
                'bail',
                'required',
                'integer',
                'max:' . app('settings')->max_credit_transfer,
                new CreditRule($this->username),
            ]
        ];
    }
}
