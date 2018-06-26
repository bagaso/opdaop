<div class="col-md-3">
    <a href="{{ route('support_tickets.create_ticket') }}" class="btn btn-primary btn-block margin-bottom">Create Ticket</a>
    <div class="box box-solid">
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li class="{{ request()->getUri() === route('support_tickets') ? 'active' : '' }}"><a href="{{ route('support_tickets') }}">All Tickets<span class="label label-primary pull-right">{{ App\Ticket::AllTickets()->count() }}</span></a></li>
                <li class="{{ request()->getUri() === route('support_tickets.open') ? 'active' : '' }}"><a href="{{ route('support_tickets.open') }}">Open Tickets<span class="label label-success pull-right">{{ App\Ticket::OpenTickets()->count() }}</span></a></li>
                <li class="{{ request()->getUri() === route('support_tickets.close') ? 'active' : '' }}"><a href="{{ route('support_tickets.close') }}">Closed Tickets<span class="label label-default pull-right">{{ App\Ticket::CLoseTickets()->count() }}</span></a></li>
                <li class="{{ request()->getUri() === route('support_tickets.lock') ? 'active' : '' }}"><a href="{{ route('support_tickets.lock') }}">Locked Tickets<span class="label label-danger pull-right">{{ App\Ticket::LockTickets()->count() }}</span></a></li>
            </ul>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /. box -->
</div>
<!-- /.col -->