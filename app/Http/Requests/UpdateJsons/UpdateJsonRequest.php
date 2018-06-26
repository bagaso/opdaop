<?php

namespace App\Http\Requests\UpdateJsons;

use App\Rules\UpdateJsons\EditJson\NameCheckExistingRule;
use App\Rules\UpdateJsons\EditJson\NameCheckExistingSlugUrlRule;
use App\UpdateJson;
use Illuminate\Foundation\Http\FormRequest;

class UpdateJsonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('MANAGE_UPDATE_JSON')) {
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
                new NameCheckExistingRule($this->id),
                New NameCheckExistingSlugUrlRule($this->id)
            ],
            'version' => 'bail|required',
            'json_data' => 'bail|required|json',
        ];
    }
}
