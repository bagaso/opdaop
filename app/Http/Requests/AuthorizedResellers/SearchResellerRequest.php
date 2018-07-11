<?php

namespace App\Http\Requests\AuthorizedResellers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SearchResellerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::check()) {
            if(auth()->user()->isAdmin() || app('settings')->enable_authorized_reseller) {
                return true;
            }
        } else {
            if(app('settings')->enable_authorized_reseller && app('settings')->public_authorized_reseller) {
                return true;
            }
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
