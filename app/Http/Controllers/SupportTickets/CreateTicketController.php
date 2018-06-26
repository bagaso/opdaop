<?php

namespace App\Http\Controllers\SupportTickets;

use App\Http\Requests\SupportTickets\CreateTicketRequest;
use App\ReplyTicket;
use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CreateTicketController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme.default.support_tickets.create_ticket');
    }

    public function create_ticket(CreateTicketRequest $request)
    {
        DB::transaction(function () use ($request) {
            $ticket = new Ticket();
            $ticket->subject = $request->subject;
            $ticket->user_id = auth()->user()->id;
            $ticket->save();
            $reply = new  ReplyTicket(['message' => $request->message, 'user_id' => auth()->user()->id]);
            $ticket->replies()->save($reply);
        });
        return redirect()->back()->with('success', 'New Ticket Created.');
    }
}
