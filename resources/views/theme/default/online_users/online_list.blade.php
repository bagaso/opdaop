@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Online Users
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Online Users</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                @if(!app('settings')->enable_online_users)
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                            Online User Page is Disabled
                        </div>
                    </div>
                @endif
                @if(app('settings')->enable_online_users)
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body table-responsive">
                                    <table class="table table-bordered table-hover" id="online_users-table" style="font-size: small">
                                        <thead>
                                        <tr>
                                            @auth
                                                @if(auth()->user()->can('MANAGE_ONLINE_USER'))
                                                    <th></th>
                                                @endif
                                            @endauth
                                            <th>Username</th>
                                            <th>Server</th>
                                            <th>DL</th>
                                            <th>UP</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                @auth
                                    @if(auth()->user()->can('MANAGE_ONLINE_USER'))
                                        <div class="panel-footer">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default">Action</button>
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="#" data-toggle="modal" data-target="#modal-disconnect_user">
                                                            Disconnect
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" data-toggle="modal" data-target="#modal-force_delete">
                                                            Force Delete
                                                        </a>
                                                    </li>
                                                    <li><a href="#">Another action</a></li>
                                                    <li><a href="#">Something else here</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="#">Separated link</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                        <!-- /.col -->
                @endif
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->

        @auth
            @if(auth()->user()->can('MANAGE_ONLINE_USER'))
                <div class="modal modal-danger fade" id="modal-disconnect_user">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Remove Confirmation</h4>
                            </div>
                            <div class="modal-body">
                                <p>Remove Selected User?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-outline" id="disconnect_user">Remove</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal remove_reseller -->

                <div class="modal modal-danger fade" id="modal-force_delete">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Remove Confirmation</h4>
                            </div>
                            <div class="modal-body">
                                <p>Delete Selected User?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-outline" id="force_delete_user">Delete</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal remove_reseller -->
            @endif
        @endauth

    </div>

    <!-- /.content-wrapper -->
@endsection

@if(app('settings')->enable_online_users)
    @push('styles')
        <link href="//datatables.yajrabox.com/css/datatables.bootstrap.css" rel="stylesheet">
        @auth
            @if(auth()->user()->can('MANAGE_ONLINE_USER'))
                <link href="//cdn.datatables.net/select/1.2.3/css/select.dataTables.min.css" rel="stylesheet">
            @endif
        @endauth
    @endpush

    @push('scripts')
        <!-- DataTables -->
        <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        @auth
            @if(auth()->user()->can('MANAGE_ONLINE_USER'))
                <script src="//cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js"></script>
            @endif
        @endauth
        <script src="//datatables.yajrabox.com/js/datatables.bootstrap.js"></script>
        <script>
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var oTable =  $('#online_users-table').DataTable({
                    order: [ {{ auth()->check() && auth()->user()->can('MANAGE_ONLINE_USER') ? 4 : 3 }}, 'desc' ],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('online_users.vpn_online_list') }}',
                        method: 'POST'
                    },
                    @auth
                    @if(auth()->user()->can('MANAGE_ONLINE_USER'))
                    columnDefs: [ {
                        searchable: false,
                        orderable: false,
                        className: 'select-checkbox',
                        targets:   0
                    } ],
                    @endif
                    @endauth
                    columns: [
                        @auth
                        @if(auth()->user()->can('MANAGE_ONLINE_USER'))
                        { data: 'check', name: 'check' },
                        @endif
                        @endauth
                        { data: 'user', name: 'user.username' },
                        { data: 'server', name: 'server.server_name' },
                        { data: 'byte_sent', name: 'byte_sent' },
                        { data: 'byte_received', name: 'byte_received' }
                    ],
                    @auth
                    @if(auth()->user()->can('MANAGE_ONLINE_USER'))
                    select: {
                        style:    'multi',
                        selector: 'td:first-child'
                    }
                    @endif
                    @endauth
                });
                @auth
                @if(auth()->user()->can('MANAGE_ONLINE_USER'))
                $("#disconnect_user").click(function () {
                    var rowcollection =  oTable.$("tr.selected");
                    //var user_ids = [];
                    var disconnect_form_builder  = '';
                    rowcollection.each(function(index,elem){
                        //Do something with 'checkbox_value'
                        var id1 = $(this).find(".ids").val();
                        disconnect_form_builder += '<input type="hidden" name="user_ids[]" value="' + id1 + '">';
                    });
                    $('<form id="form_remove_user" action="{{ route('online_users.vpn_online_list.disconnect') }}" method="post">')
                        .append('{{ csrf_field() }}')
                        .append(disconnect_form_builder)
                        .append('</form>')
                        .appendTo($(document.body)).submit();
                });
                $("#force_delete_user").click(function () {
                    var rowcollection =  oTable.$("tr.selected");
                    //var user_ids = [];
                    var delete_form_builder  = '';
                    rowcollection.each(function(index,elem){
                        //Do something with 'checkbox_value'
                        var id1 = $(this).find(".ids").val();
                        delete_form_builder += '<input type="hidden" name="ids[]" value="' + id1 + '">';
                    });
                    $('<form id="form_delete_user" action="{{ route('online_users.vpn_online_list.force_delete') }}" method="post">')
                        .append('{{ csrf_field() }}')
                        .append(delete_form_builder)
                        .append('</form>')
                        .appendTo($(document.body)).submit();
                });
                @endif
                @endauth
            });
        </script>
    @endpush
@endif