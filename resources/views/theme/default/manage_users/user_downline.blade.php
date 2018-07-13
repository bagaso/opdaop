@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User Downline
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Manage Users</a></li>
                <li class="active">User Downline</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="box box-primary">
                        @include('theme.default.layouts.sidebar.manage_users_profile')
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        @include('theme.default.layouts.menu.manage_users_profile')
                        <div class="tab-content">
                            <div class="active">

                                @if(auth()->user()->cannot('ACCESS_USER_DOWNLINE_ID', $user->id) || auth()->user()->cannot('MANAGE_USER_OTHER'))
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                                        No Permission Acess User Downline.
                                    </div>
                                @endif
                                @if(auth()->user()->can('ACCESS_USER_DOWNLINE_ID', $user->id) && auth()->user()->can('MANAGE_USER_OTHER'))

                                            <div class="panel-body table-responsive">
                                                <table class="table table-bordered table-hover" id="users-table">
                                                    <thead>
                                                    <tr style="font-size: small">
                                                        <th></th>
                                                        <th>Username</th>
                                                        <th>User Group</th>
                                                        <th>Package</th>
                                                        <th>Status</th>
                                                        <th>Credits</th>
                                                        <th>Duration</th>
                                                        <th>Upline</th>
                                                        <th>Joined Date</th>
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

@can('ACCESS_USER_DOWNLINE_ID', $user->id)
    @push('styles')
    <link href="//datatables.yajrabox.com/css/datatables.bootstrap.css" rel="stylesheet">
    @endpush

    @push('scripts')
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="//datatables.yajrabox.com/js/datatables.bootstrap.js"></script>
    <script>
        $(function () {
//        $('input').iCheck({
//            checkboxClass: 'icheckbox_square-blue',
//            radioClass: 'iradio_square-blue',
//            increaseArea: '15%' // optional
//        });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#users-table').DataTable({
                order: [ 8, 'desc' ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('manage_users.user_list.downline', $user->id) }}',
                    method: 'POST'
                },
                columns: [
                    { data: 'check', name: 'check' },
                    { data: 'username', name: 'username' },
                    { data: 'group', name: 'group.name' },
                    { data: 'subscription', name: 'subscription.name' },
                    { data: 'status', name: 'status.name_get' },
                    { data: 'credits', name: 'credits' },
                    { data: 'expired_at', name: 'expired_at' },
                    { data: 'upline', name: 'upline' },
                    { data: 'created_at', name: 'created_at' }
                ]
            });
        });
    </script>
    @endpush
@endcan