<?php

namespace App\Http\Requests\Account;

use App\Rules\Account\Duration\InputCreditRule;
use Illuminate\Foundation\Http\FormRequest;

class DurationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(!auth()->user()->isAdmin() && auth()->user()->can('ACCOUNT_EXTEND_USING_CREDITS')) {
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
                'between:1,3',
                new InputCreditRule(),
            ],
        ];
    }
}
