<?php

namespace App\Http\Requests\SupportTickets;

use App\Ticket;
use Illuminate\Foundation\Http\FormRequest;

class ReplyTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $ticket = Ticket::findorfail($this->id);
        if(auth()->user()->can('MANAGE_TICKET', $this->id) && !$ticket->is_lock) {
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
            'message' => 'bail|required',
        ];
    }
}
