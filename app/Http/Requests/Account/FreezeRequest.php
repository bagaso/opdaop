<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class FreezeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->isActive()) {
            # Disallow if user is 'Expired'
            if(auth()->user()->expired_at == 'Expired') {
                return false;
            }
            # Disallow if account has no permission to bypass USER_BYPASS_FREEZE_LIMIT and user is no freeze left
            if(auth()->user()->freeze_ctr < 1 && auth()->user()->cannot('BYPASS_FREEZE_LIMIT')) {
                return false;
            }
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
            //
        ];
    }
}
