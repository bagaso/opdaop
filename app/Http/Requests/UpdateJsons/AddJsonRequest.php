<?php

namespace App\Http\Requests\UpdateJsons;

use App\Rules\UpdateJsons\AddJson\NameCheckExistingRule;
use App\Rules\UpdateJsons\AddJson\NameCheckExistingSlugUrlRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddJsonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->isAdmin()) {
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
            'json_name' => [
                'bail',
                'required',
                //'unique:update_jsons,name',
                new NameCheckExistingRule,
                New NameCheckExistingSlugUrlRule
            ],
            'version' => 'bail|required',
            'json_data' => 'bail|required|json',
        ];
    }
}
