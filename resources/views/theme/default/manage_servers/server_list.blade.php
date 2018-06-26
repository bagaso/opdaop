@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Manage Server
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Manage Server</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @cannot('MANAGE_SERVER')
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                            No Permission to Manage Server.
                        </div>
                    </div>
                @endcannot
                @can('MANAGE_SERVER')
                    @include('theme.default.layouts.sidebar.manage_servers')
                    <div class="col-md-9">

                        @if (session('success_delete'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-check"></i> Success!</h4>
                                {{ session('success_delete') }}
                            </div>
                        @endif
                        @if (session('error_cloudflare'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Cloudflare Error!</h4>
                                {{ session('error_cloudflare') }}
                            </div>
                        @endif
                        @if ($errors->count())
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                Server Update Failed.
                            </div>
                        @endif
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Server List</h3>
                            </div>
                            <div class="panel-body table-responsive">
                                <table class="table table-hover" id="servers-table" style="font-size: small">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Server Name</th>
                                            <th>Server IP</th>
                                            <th>Sub-Domain</th>
                                            <th>Online</th>
                                            <th>Access</th>
                                            <th>Subscriptions</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="panel-footer">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default">Action</button>
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a href="#" data-toggle="modal" data-target="#modal-delete_server">
                                                Delete
                                            </a>
                                        </li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.col -->
                @endcan
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->

        <div class="modal modal-danger fade" id="modal-delete_server">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Delete Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p>Delete Selected Server?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-outline" id="delete_server">Delete</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal delete_server -->

    </div>
    <!-- /.content-wrapper -->
@endsection

@can('MANAGE_SERVER')
    @push('styles')
        <link href="//datatables.yajrabox.com/css/datatables.bootstrap.css" rel="stylesheet">
        <link href="//cdn.datatables.net/select/1.2.3/css/select.dataTables.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <!-- DataTables -->
        <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js"></script>
        <script src="//datatables.yajrabox.com/js/datatables.bootstrap.js"></script>
        <script>
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var oTable =  $('#servers-table').DataTable({
                    order: [ 1, 'desc' ],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('manage_servers.server_list') }}',
                        method: 'POST'
                    },
                    columnDefs: [ {
                        searchable: false,
                        orderable: false,
                        className: 'select-checkbox',
                        targets:   0
                    } ],
                    columns: [
                        { data: 'check', name: 'check' },
                        { data: 'server_name', name: 'server_name' },
                        { data: 'server_ip', name: 'server_ip' },
                        { data: 'sub_domain', name: 'sub_domain' },
                        { data: 'online_users', name: 'online_users' },
                        { data: 'server_access', name: 'server_access.name' },
                        { data: 'subscriptions', name: 'subscriptions.name', orderable: false, },
                        { data: 'is_active', name: 'is_active' }
                    ],
                    select: {
                        style:    'multi',
                        selector: 'td:first-child'
                    }
                });
                $("#delete_server").click(function () {
                    var rowcollection =  oTable.$("tr.selected");
                    //var user_ids = [];
                    var delete_form_builder  = '';
                    rowcollection.each(function(index,elem){
                        //Do something with 'checkbox_value'
                        var server_id = $(this).find(".server_id").val();
                        delete_form_builder += '<input type="hidden" name="server_ids[]" value="' + server_id + '">';
                    });
                    $('<form id="form_delete_user" action="{{ route('manage_servers.delete') }}" method="post">')
                        .append('{{ csrf_field() }}')
                        .append(delete_form_builder)
                        .append('</form>')
                        .appendTo($(document.body)).submit();
                });
            });
        </script>
    @endpush
@endcan