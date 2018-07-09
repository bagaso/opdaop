<?php

namespace App\Http\Controllers\SupportTickets;

use App\Http\Requests\SupportTickets\CloseMultiTicketRequest;
use App\Http\Requests\SupportTickets\DeleteTicketRequest;
use App\Http\Requests\SupportTickets\LockMultiTicketRequest;
use App\Http\Requests\SupportTickets\OpenMultiTicketRequest;
use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
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
        return view('theme.default.support_tickets.ticket_list');
    }

    public function ticket_list()
    {
        $query = Ticket::AllTickets()->selectRaw('tickets.id, tickets.subject, tickets.is_open, tickets.is_lock');
        return datatables()->eloquent($query)
            ->addColumn('check', '<input type="hidden" class="ticket_id" value="{{ $id }}">')
            ->addColumn('ticketOwner', function (Ticket $ticket) {
                return $ticket->ticketOwner->user->username;
            })
            ->addColumn('latestReply', function (Ticket $ticket) {
                return $ticket->latestReply->user->username;
            })
            ->addColumn('status', function (Ticket $ticket) {
                return '<span class="label label-' . $ticket->status_class . '">' . $ticket->status . '</span>';
            })
            ->editColumn('id', function (Ticket $ticket) {
                return '<a href="' . route('support_tickets.view_ticket', $ticket->id) . '">#' . $ticket->id . '</a>';
            })
            ->filterColumn('status', function ($query, $keyword) {
                if(str_contains('open', strtolower($keyword))) {
                    $query->where('is_open', '=', 1)->where('is_lock', '=', 0);
                } else if(str_contains('closed', strtolower($keyword))) {
                    $query->where('is_open', '=', 0)->where('is_lock', '=', 0);
                } else if(str_contains('locked', strtolower($keyword))) {
                    $query->where('is_lock', '=', 1);
                } else {
                    //
                }
            })
            ->rawColumns(['check', 'id', 'status'])
            ->make(true);
    }

    public function multi_close(CloseMultiTicketRequest $request)
    {
        foreach ($request->ticket_ids as $id) {
            if(auth()->user()->can('MANAGE_TICKET', $id)) {
                return redirect()->back()->with('error', 'Closing Tickets Failed.');
            }
        }
        DB::transaction(function () use ($request) {
            foreach ($request->ticket_ids as $id) {
                $ticket = Ticket::findorfail($id);
                $ticket->is_open = 0;
                $ticket->save();
            }
        });
        return redirect()->back()->with('success', 'Selected Ticket Closed.');
    }

    public function multi_lock(LockMultiTicketRequest $request)
    {
        foreach ($request->ticket_ids as $id) {
            if(auth()->user()->can('MANAGE_TICKET', $id)) {
                return redirect()->back()->with('error', 'Locking Tickets Failed.');
            }
        }
        DB::transaction(function () use ($request) {
            foreach ($request->ticket_ids as $id) {
                $ticket = Ticket::findorfail($id);
                $ticket->is_lock = 1;
                $ticket->save();
            }
        });
        return redirect()->back()->with('success', 'Selected Ticket Locked.');
    }

    public function multi_open(OpenMultiTicketRequest $request)
    {
        foreach ($request->ticket_ids as $id) {
            if(auth()->user()->can('MANAGE_TICKET', $id)) {
                return redirect()->back()->with('error', 'Opening Tickets Failed.');
            }
        }
        DB::transaction(function () use ($request) {
            foreach ($request->ticket_ids as $id) {
                $ticket = Ticket::findorfail($id);
                $ticket->is_open = 1;
                $ticket->save();
            }
        });
        return redirect()->back()->with('success', 'Selected Ticket Opened.');
    }

    public function delete(DeleteTicketRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->ticket_ids as $id) {
                $ticket = Ticket::findorfail($id);
                $ticket->replies()->delete();
                $ticket->delete();
            }
        });
        return redirect()->back()->with('success', 'Selected Ticket Deleted.');
    }
}
