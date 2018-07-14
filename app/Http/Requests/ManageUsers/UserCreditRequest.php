<?php

namespace App\Http\Requests\ManageUsers;

use App\Rules\ManageUsers\UserCredit\CreditRule;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserCreditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('TRANSFER_USER_CREDIT_ID', $this->id)) {
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
            'credits' => [
                'bail',
                'required',
                'integer',
                $this->top_up === 'on' ? 'min:1' : (auth()->user()->can('SUBTRACT_CREDIT', $this->id) ? 'min:-20' : 'min:1'),
                $this->top_up === 'on' ? 'max:3' : 'max:' . app('settings')->max_credit_transfer,
                new CreditRule($this->id)
            ]
        ];
    }
}
