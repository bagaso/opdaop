<?php

namespace App\Http\Requests\SupportTickets;

use App\Rules\SupportTickets\OpenTicketIds;
use Illuminate\Foundation\Http\FormRequest;

class OpenMultiTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
