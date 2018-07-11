@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                View Ticket
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Support Ticket</a></li>
                <li class="active">View Ticket</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @include('theme.default.layouts.sidebar.view_ticket')
                <div class="col-md-9">

                    <!-- DIRECT CHAT -->
                    <div class="box box-primary direct-chat direct-chat-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ticket : #{{ $ticket->id }}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Conversations are loaded here -->
                            <div class="direct-chat-messages" style="height: auto;">
                                <!-- Message. Default to the left -->
                                @foreach($ticket->replies as $reply)
                                    <div class="direct-chat-msg {{ $ticket->user_id === $reply->user_id  ? 'right' : 'left'}}">
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-name pull-{{ $ticket->user_id === $reply->user_id  ? 'right' : 'left'}}">{{ $reply->user->username }} - <small class="label label-{{ $reply->user->username <> '###' ? $reply->user->group->class :  'warning' }}">{{ $reply->user->username <> '###' ? $reply->user->group->name : 'Deleted' }}</small></span>
                                            <span class="direct-chat-timestamp pull-{{ $ticket->user_id === $reply->user_id  ? 'left' : 'right'}}">{{ $reply->created_at }}</span>
                                        </div>
                                        <!-- /.direct-chat-info -->
                                        <img class="direct-chat-img" src="/theme/default/dist/img/user1-128x128.jpg" alt="message user image">
                                        <!-- /.direct-chat-img -->
                                        <div class="direct-chat-text">
                                            {{ $reply->message }}
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                    <!-- /.direct-chat-msg -->
                                @endforeach
                            </div>
                            <!--/.direct-chat-messages-->

                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <form action="{{ route('support_tickets.view_ticket.reply', $ticket->id) }}" method="post">
                                {{ csrf_field() }}
                                <div class="input-group{{ $errors->has('message') ? ' has-error' : '' }}">
                                    <input type="text" name="message" placeholder="Type Message ..." class="form-control" autocomplete="off" {{ $ticket->is_lock ? ' disabled' : '' }}>
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-warning btn-flat"{{ $ticket->is_lock ? ' disabled' : '' }}>Send</button>
                                    </span>
                                </div>
                                @if ($errors->has('message'))
                                    <span class="help-block" style="color: maroon;">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                @endif
                            </form>
                        </div>
                        <!-- /.box-footer-->
                    </div>
                    <!--/.direct-chat -->

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->

@endsection