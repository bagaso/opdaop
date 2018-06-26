<?php

namespace App\Http\Controllers\SupportTickets;

use App\Http\Requests\SupportTickets\CloseTicketRequest;
use App\Http\Requests\SupportTickets\LockTicketRequest;
use App\Http\Requests\SupportTickets\ReplyTicketRequest;
use App\ReplyTicket;
use App\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ViewTicketController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'access_page.support_tickets']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = 0)
    {
        $ticket = Ticket::findorfail($id);
        return view('theme.default.support_tickets.view_ticket', compact('ticket'));
    }

    public function reply(ReplyTicketRequest $request, $id = 0)
    {
        $ticket = Ticket::findorfail($id);
        $reply = new  ReplyTicket(['message' => $request->message, 'user_id' => auth()->user()->id]);
        $ticket->replies()->save($reply);
        $ticket->is_open = 1;
        $ticket->updated_at = Carbon::now();
        $ticket->save();

        return redirect()->back();
    }

    public function close(CloseTicketRequest $request, $id)
    {
        $ticket = Ticket::findorfail($id);
        $ticket->is_open = 0;
        $ticket->save();
        return redirect()->back();
    }

    public function lock(LockTicketRequest $request, $id)
    {
        $ticket = Ticket::findorfail($id);
        $ticket->is_lock = 1;
        $ticket->save();
        return redirect()->back();
    }

    public function unlock(LockTicketRequest $request, $id)
    {
        $ticket = Ticket::findorfail($id);
        $ticket->is_lock = 0;
        $ticket->save();
        return redirect()->back();
    }
}
