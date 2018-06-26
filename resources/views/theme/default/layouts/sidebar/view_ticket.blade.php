<div class="col-md-3">
    <div class="box box-primary">
        <div class="box-body box-profile">
            <ul class="list-group">
                <li class="list-group-item">
                    <b>Subject</b> <a class="pull-right">{{ $ticket->subject }}</a>
                </li>
                <li class="list-group-item">
                    <b>Status</b> <a class="pull-right"><span class="label label-{{ $ticket->status_class }}">{{ $ticket->status }}</span></a>
                </li>
            </ul>

            <button class="btn btn-default btn-block" onclick="event.preventDefault(); document.getElementById('close-ticket-form').submit();"{{ !$ticket->is_open ? ' disabled' : '' }}>Close</button>
            @if(auth()->user()->isAdmin())
                @if(!$ticket->is_lock)
                    <button class="btn btn-danger btn-block" onclick="event.preventDefault(); document.getElementById('lock-ticket-form').submit();">Lock</button>
                @else
                    <button class="btn btn-danger btn-block" onclick="event.preventDefault(); document.getElementById('unlock-ticket-form').submit();">Unlock</button>
                @endif
            @endif

            <form id="close-ticket-form" action="{{ route('support_tickets.view_ticket.close', $ticket->id) }}" method="post" style="display: none;">
                {{ csrf_field() }}
            </form>
            @if(auth()->user()->isAdmin())
                @if(!$ticket->is_lock)
                    <form id="lock-ticket-form" action="{{ route('support_tickets.view_ticket.lock', $ticket->id) }}" method="post" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                @else
                    <form id="unlock-ticket-form" action="{{ route('support_tickets.view_ticket.unlock', $ticket->id) }}" method="post" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                @endif
            @endif
        </div>
        <!-- /.box-body -->
    </div>
</div>
<!-- /.col -->