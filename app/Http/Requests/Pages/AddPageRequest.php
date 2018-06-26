<?php

namespace App\Http\Requests\Pages;

use App\Rules\Pages\AddPage\PageNameRule;
use Illuminate\Foundation\Http\FormRequest;

class AddPageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('MANAGE_PAGES')) {
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
            'page_name' => [
                'bail',
                'required',
                new PageNameRule()
            ],
            'html_content' => [
                'bail',
                'required',
            ]
        ];
    }
}
