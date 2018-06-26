@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Pages
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Pages</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @include('theme.default.layouts.sidebar.pages')
                <div class="col-md-9">

                    @if (session('success_delete'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                            {{ session('success_delete') }}
                        </div>
                    @endif

                    <div class="panel panel-default">
                        <div class="panel-body table-responsive">
                            <table class="table table-bordered table-hover" id="pages-table" style="font-size: small">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Page Name</th>
                                    <th>Slug Url</th>
                                    <th>Link</th>
                                    <th>Public</th>
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
                                        <a href="#" data-toggle="modal" data-target="#modal-delete_page">
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

        <div class="modal modal-danger fade" id="modal-delete_page">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Delete Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p>Delete Seleted Page?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-outline" id="delete_page">Delete</button>
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
            var oTable =  $('#pages-table').DataTable({
                order: [ 1, 'desc' ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('pages.list') }}',
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
                    { data: 'name', name: 'name' },
                    { data: 'slug_url', name: 'slug_url' },
                    { data: 'link', name: 'link', orderable: false },
                    { data: 'is_public', name: 'is_public' }
                ],
                select: {
                    style:    'multi',
                    selector: 'td:first-child'
                }
            });

            $("#delete_page").click(function () {
                var rowcollection =  oTable.$("tr.selected");
                //var user_ids = [];
                var delete_form_builder  = '';
                rowcollection.each(function(index,elem){
                    //Do something with 'checkbox_value'
                    var page_id = $(this).find(".page_id").val();
                    delete_form_builder += '<input type="hidden" name="page_ids[]" value="' + page_id + '">';
                });
                $('<form id="form_delete_page" action="{{ route('pages.delete') }}" method="post">')
                    .append('{{ csrf_field() }}')
                    .append(delete_form_builder)
                    .append('</form>')
                    .appendTo($(document.body)).submit();
            });
        });
    </script>
@endpush