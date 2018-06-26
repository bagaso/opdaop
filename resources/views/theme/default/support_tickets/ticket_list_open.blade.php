@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Open Ticket
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Support Ticket</a></li>
                <li class="active">Open Ticket</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @include('theme.default.layouts.sidebar.support_tickets')
                <div class="col-md-9">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Open Ticket List
                            </h3>
                        </div>
                        <div class="panel-body table-responsive">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                                    {{ session('success') }}
                                </div>
                            @endif
                            <table class="table table-bordered table-hover" id="users-table" style="font-size: small">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Ticket ID</th>
                                    <th>Subject</th>
                                    <th>Username</th>
                                    <th>Last Reply</th>
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
                                        <a href="#" data-toggle="modal" data-target="#modal-delete_ticket">
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
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->

        <div class="modal modal-danger fade" id="modal-delete_ticket">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Delete Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p>Delete Selected Ticket?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-outline" id="delete_ticket">Delete</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal delete_user -->

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
            var oTable =  $('#users-table').DataTable({
                order: [ 1, 'desc' ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('support_tickets.list.open') }}',
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
                    { data: 'id', name: 'id' },
                    { data: 'subject', name: 'subject' },
                    { data: 'ticketOwner', name: 'ticketOwner.user.username' },
                    { data: 'latestReply', name: 'latestReply.user.username' },
                    { data: 'status', name: 'status', orderable: false }
                ],
                select: {
                    style:    'multi',
                    selector: 'td:first-child'
                }
            });

            $("#delete_ticket").click(function () {
                var rowcollection =  oTable.$("tr.selected");
                //var user_ids = [];
                var delete_form_builder  = '';
                rowcollection.each(function(index,elem){
                    //Do something with 'checkbox_value'
                    var ticket_id = $(this).find(".ticket_id").val();
                    delete_form_builder += '<input type="hidden" name="ticket_ids[]" value="' + ticket_id + '">';
                });
                $('<form id="form_delete_ticket" action="{{ route('support_tickets.delete') }}" method="post">')
                    .append('{{ csrf_field() }}')
                    .append(delete_form_builder)
                    .append('</form>')
                    .appendTo($(document.body)).submit();
            });
        });
    </script>
@endpush