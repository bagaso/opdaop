@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User Voucher
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Manage Users</a></li>
                <li class="active">User Voucher</li>
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
                                @cannot('MANAGE_USER_VOUCHER_ID', $user->id)
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                                        No Permission Access User Voucher.
                                    </div>
                                @endcannot
                                @can('MANAGE_USER_VOUCHER_ID', $user->id)

                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    @if ($errors->count())
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                            User Voucher Apply Failed.
                                        </div>
                                    @endif

                                    <form action="{{ route('manage_users.vouchers.apply', $user->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}

                                        <div class="form-group{{ $errors->has('voucher') ? ' has-error' : '' }}">
                                            <label for="voucher" class="col-sm-3 control-label">Voucher</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="voucher" name="voucher" placeholder="Voucher Code">
                                                @if ($errors->has('voucher'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('voucher') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button type="submit" class="btn btn-danger">Submit</button>
                                            </div>
                                        </div>
                                    </form>



                                @endcan

                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->

                    @can('MANAGE_USER_VOUCHER_ID', $user->id)
                        <div class="panel panel-default">
                            <div class="panel-body table-responsive">
                                <table class="table table-bordered table-hover" id="vouchers-table" style="font-size: small">
                                    <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Duration</th>
                                        <th>Created By</th>
                                        <th>Date Used</th>
                                        <th>Date Created</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    @endcan

                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->
@endsection

@can('MANAGE_USER_VOUCHER_ID', $user->id)
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
                $('#vouchers-table').DataTable({
                    order: [ 3, 'desc' ],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('manage_users.vouchers.list', $user->id) }}',
                        method: 'POST'
                    },
                    columns: [
                        { data: 'code', name: 'code' },
                        { data: 'duration', name: 'duration' },
                        { data: 'user_from', name: 'user_from.username' },
                        { data: 'updated_at', name: 'updated_at' },
                        { data: 'created_at', name: 'created_at' }
                    ]
                });
            });
        </script>
    @endpush
@endcan