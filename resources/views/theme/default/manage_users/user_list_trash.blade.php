@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Trash User List
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Manage Users</a></li>
                <li class="active">Trash User List</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @include('theme.default.layouts.sidebar.manage_users')
                <div class="col-md-9">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default">Action</button>
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#" data-toggle="modal" data-target="#modal-restore_user">Restore</a></li>
                                    <li><a href="#" data-toggle="modal" data-target="#modal-force_delete_user">Force Delete</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body table-responsive">
                            <table class="table table-bordered table-hover" id="users-table" style="font-size: small">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Username</th>
                                    <th>User Group</th>
                                    <th>Package</th>
                                    <th>Status</th>
                                    <th>Credits</th>
                                    <th>Duration</th>
                                    <th>Upline</th>
                                    <th>Deleted At</th>
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
                                    <li><a href="#" data-toggle="modal" data-target="#modal-restore_user">Restore</a></li>
                                    <li><a href="#" data-toggle="modal" data-target="#modal-force_delete_user">Force Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->

        <div class="modal modal-warning fade" id="modal-restore_user">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p>Restore Selected User?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-outline" id="restore_user">Restore</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal restore_user -->

        <div class="modal modal-warning fade" id="modal-force_delete_user">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p>Force Delete Selected User?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-outline" id="force_delete_user">Force Delete</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal force_delete_user -->

    </div>
    <!-- /.content-wrapper -->
@endsection

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
            var oTable =  $('#users-table').DataTable({
                order: [ 8, 'desc' ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('manage_users.user_list.trash') }}',
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
                    { data: 'username', name: 'username' },
                    { data: 'group', name: 'group.name' },
                    { data: 'subscription', name: 'subscription.name' },
                    { data: 'status', name: 'status.name_get' },
                    { data: 'credits', name: 'credits' },
                    { data: 'expired_at', name: 'expired_at' },
                    { data: 'upline', name: 'upline' },
                    { data: 'deleted_at', name: 'deleted_at' }
                ],
                select: {
                    style:    'multi',
                    selector: 'td:first-child'
                }
            });

            $("#restore_user").click(function () {
                var rowcollection =  oTable.$("tr.selected");
                //var user_ids = [];
                var restore_user_form_builder  = '';
                rowcollection.each(function(index,elem){
                    //Do something with 'checkbox_value'
                    var user_id = $(this).find(".user_id").val();
                    restore_user_form_builder += '<input type="hidden" name="user_ids[]" value="' + user_id + '">';
                });
                $('<form id="form_restore_user" action="{{ route('manage_users.restore_user') }}" method="post">')
                    .append('{{ csrf_field() }}')
                    .append(restore_user_form_builder)
                    .append('</form>')
                    .appendTo($(document.body)).submit();
            });

            $("#force_delete_user").click(function () {
                var rowcollection =  oTable.$("tr.selected");
                //var user_ids = [];
                var force_delete_user_form_builder  = '';
                rowcollection.each(function(index,elem){
                    //Do something with 'checkbox_value'
                    var user_id = $(this).find(".user_id").val();
                    force_delete_user_form_builder += '<input type="hidden" name="user_ids[]" value="' + user_id + '">';
                });
                $('<form id="form_force_delete_user" action="{{ route('manage_users.force_delete_user') }}" method="post">')
                    .append('{{ csrf_field() }}')
                    .append(force_delete_user_form_builder)
                    .append('</form>')
                    .appendTo($(document.body)).submit();
            });
        });
    </script>
@endpush