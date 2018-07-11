@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Json File
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Json File</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    @can('MANAGE_UPDATE_JSON')
                        @if (session('success_delete'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-check"></i> Success!</h4>
                                {{ session('success_delete') }}
                            </div>
                        @endif
                    <div class="panel panel-default">
                        <div class="panel-body table-responsive">
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('json.create') }}" class="btn btn-primary margin-bottom">Create Json</a>
                            @endif
                            <table class="table table-bordered table-hover" id="json_file-table" style="font-size: small">
                                <thead>
                                    <tr>
                                        @if(auth()->user()->isAdmin())
                                        <th></th>
                                        @endif
                                        <th>Name</th>
                                        <th>Slug Url</th>
                                        <th>Version</th>
                                        <th>Last Update</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                            @if(auth()->user()->isAdmin())
                                <div class="panel-footer">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default">Action</button>
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="#" data-toggle="modal" data-target="#modal-delete_json">
                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                    </div>
                    @endcan
                    @cannot('MANAGE_UPDATE_JSON')
                    <div class="alert alert-warning alert-dismissible">
                        <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                        No Permission to Manage Json File.
                    </div>
                    @endcannot
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->

            @if(auth()->user()->isAdmin())
                <div class="modal modal-danger fade" id="modal-delete_json">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Confirmation</h4>
                            </div>
                            <div class="modal-body">
                                <p>Delete Selected Json File?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-outline" id="delete_json">Delete</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal delete_json -->
            @endif

    </div>
    <!-- /.content-wrapper -->
@endsection

@push('styles')
    <link href="//datatables.yajrabox.com/css/datatables.bootstrap.css" rel="stylesheet">
    @if(auth()->user()->isAdmin())
    <link href="//cdn.datatables.net/select/1.2.3/css/select.dataTables.min.css" rel="stylesheet">
    @endif
@endpush

@push('scripts')
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    @if(auth()->user()->isAdmin())
    <script src="//cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js"></script>
    @endif
    <script src="//datatables.yajrabox.com/js/datatables.bootstrap.js"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var oTable = $('#json_file-table').DataTable({
                order: [ {{ auth()->user()->isAdmin() ? 5 : 4 }}, 'desc' ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('json.list') }}',
                    method: 'POST'
                },
                @if(auth()->user()->isAdmin())
                columnDefs: [ {
                    searchable: false,
                    orderable: false,
                    className: 'select-checkbox',
                    targets:   0
                } ],
                @endif
                columns: [
                    @if(auth()->user()->isAdmin())
                    { data: 'check', name: 'check' },
                    @endif
                    { data: 'name', name: 'name' },
                    { data: 'link', name: 'link' },
                    { data: 'version', name: 'version' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'created_at', name: 'created_at' }
                ],
                @if(auth()->user()->isAdmin())
                select: {
                    style:    'multi',
                    selector: 'td:first-child'
                }
                @endif
            });
            @if(auth()->user()->isAdmin())
            $("#delete_json").click(function () {
                var rowcollection =  oTable.$("tr.selected");
                //var user_ids = [];
                var delete_form_builder  = '';
                rowcollection.each(function(index,elem){
                    //Do something with 'checkbox_value'
                    var json_id = $(this).find(".json_id").val();
                    delete_form_builder += '<input type="hidden" name="json_ids[]" value="' + json_id + '">';
                });
                $('<form id="form_delete_user" action="{{ route('json.delete') }}" method="post">')
                    .append('{{ csrf_field() }}')
                    .append(delete_form_builder)
                    .append('</form>')
                    .appendTo($(document.body)).submit();
            });
            @endif
        });
    </script>
@endpush