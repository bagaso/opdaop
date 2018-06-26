@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Action Logs
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Account</a></li>
                <li class="active">Action Logs</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="box box-primary">
                        @include('theme.default.layouts.sidebar.account')
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        @include('theme.default.layouts.menu.account')
                        <div class="tab-content">
                            <div class="active">
                                @if(!auth()->user()->isActive())
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                        Account is Inactive.
                                    </div>
                                @endif
                                @if(auth()->user()->isActive())
                                    <div class="panel-body table-responsive">
                                        <table class="table table-hover" id="account-logs-action" style="font-size: small">
                                            <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Action</th>
                                                <th>From IP</th>
                                                <th>DateTime</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                @endif
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection

@if(auth()->user()->isActive())
    @push('styles')
        <link href="//datatables.yajrabox.com/css/datatables.bootstrap.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <!-- DataTables -->
        <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script src="//datatables.yajrabox.com/js/datatables.bootstrap.js"></script>
        <script>
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#account-logs-action').DataTable({
                    order: [ 3, 'desc' ],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('account.action_logs.list') }}',
                        method: 'POST'
                    },
                    columns: [
                        { data: 'user_related', name: 'user_related.username' },
                        { data: 'action', name: 'action' },
                        { data: 'from_ip', name: 'from_ip' },
                        { data: 'created_at', name: 'created_at' },
                    ]
                });
            });
        </script>
    @endpush
@endif