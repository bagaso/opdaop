@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Sub-Reseller User List
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Manage Users</a></li>
                <li class="active">Sub-Reseller User List</li>
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
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    @can('DELETE_USER')
                                    <li class="divider"></li>
                                    <li><a href="#" data-toggle="modal" data-target="#modal-delete_user">Delete</a></li>
                                    @endcan
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body table-responsive">
                            <table class="table table-bordered table-hover" id="users-table" style="font-size: small">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Username</th>
                                    <th>Group</th>
                                    <th>Subscription</th>
                                    <th>Status</th>
                                    <th>Credits</th>
                                    <th>Duration</th>
                                    <th>Upline</th>
                                    <th>Joined Date</th>
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
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    @can('DELETE_USER')
                                    <li class="divider"></li>
                                    <li><a href="#" data-toggle="modal" data-target="#modal-delete_user">Delete</a></li>
                                    @endcan
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

        @can('DELETE_USER')
        <div class="modal modal-danger fade" id="modal-delete_user">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p>Delete Selected User?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-outline" id="delete_user">Delete</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal delete_user -->
        @endcan

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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var oTable = $('#users-table').DataTable({
                order: [ 8, 'desc' ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('manage_users.user_list.sub_reseller') }}',
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
                    { data: 'created_at', name: 'created_at' }
                ],
                select: {
                    style:    'multi',
                    selector: 'td:first-child'
                }
            });
            @can('DELETE_USER')
            $("#delete_user").click(function () {
                var rowcollection =  oTable.$("tr.selected");
                //var user_ids = [];
                var delete_form_builder  = '';
                rowcollection.each(function(index,elem){
                    //Do something with 'checkbox_value'
                    var user_id = $(this).find(".user_id").val();
                    delete_form_builder += '<input type="hidden" name="user_ids[]" value="' + user_id + '">';
                });
                $('<form id="form_delete_user" action="{{ route('manage_users.user_list.all.delete') }}" method="post">')
                    .append('{{ csrf_field() }}')
                    .append(delete_form_builder)
                    .append('</form>')
                    .appendTo($(document.body)).submit();
            });
            @endcan
        });
    </script>
@endpush