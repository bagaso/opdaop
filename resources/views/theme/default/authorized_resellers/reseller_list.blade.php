@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Authorized Resellers
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Authorized Resellers</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    @auth
                    <div class="panel panel-default">
                        <div class="panel-body table-responsive">
                            <table class="table table-hover" id="resellers-table" style="font-size: small">
                                <thead>
                                    <tr>
                                        @if(auth()->user()->can('REMOVE_DISTRIBUTOR'))
                                        <th></th>
                                        @endif
                                        <th>Name</th>
                                        <th>Group</th>
                                        <th>Contact</th>
                                        <th>Credits</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        @if(auth()->user()->can('REMOVE_DISTRIBUTOR'))
                        <div class="panel-footer">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default">Action</button>
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#modal-remove_reseller">
                                            Remove
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
                    </div>
                    @endauth

                    @guest
                    @if(app('settings')->public_authorized_reseller)
                    <div class="panel panel-default">
                        <div class="panel-body table-responsive">
                            <table class="table table-hover" id="resellers-table" style="font-size: small">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Group</th>
                                        <th>Contact</th>
                                        <th>Credits</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning alert-dismissible">
                        <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                        Public Authorized Reseller Page is Disabled.
                    </div>
                    @endif
                    @endguest
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->

        @auth
            @if(auth()->user()->can('REMOVE_DISTRIBUTOR'))
                <div class="modal modal-danger fade" id="modal-remove_reseller">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Remove Confirmation</h4>
                            </div>
                            <div class="modal-body">
                                <p>Remove Selected Reseller?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-outline" id="remove_reseller">Remove</button>
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

@push('styles')
    <link href="//datatables.yajrabox.com/css/datatables.bootstrap.css" rel="stylesheet">
    @auth
    @if(auth()->user()->can('REMOVE_DISTRIBUTOR'))
        <link href="//cdn.datatables.net/select/1.2.3/css/select.dataTables.min.css" rel="stylesheet">
    @endif
    @endauth
@endpush

@push('scripts')
<!-- DataTables -->
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
@auth
@if(auth()->user()->can('REMOVE_DISTRIBUTOR'))
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
        var oTable =  $('#resellers-table').DataTable({
            order: [ {{ auth()->check() && auth()->user()->can('REMOVE_DISTRIBUTOR') ? 4 : 3 }}, 'desc' ],
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('authorized_reseller.list') }}',
                method: 'POST'
            },
            @auth
            @if(auth()->user()->can('REMOVE_DISTRIBUTOR'))
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
                @if(auth()->user()->can('REMOVE_DISTRIBUTOR'))
                { data: 'check', name: 'check' },
                @endif
                @endauth
                { data: 'fullname', name: 'fullname' },
                { data: 'group', name: 'group.name' },
                { data: 'contact', name: 'contact' },
                { data: 'credits', name: 'credits' }
            ],
            @auth
            @if(auth()->user()->can('REMOVE_DISTRIBUTOR'))
            select: {
                style:    'multi',
                selector: 'td:first-child'
            }
            @endif
            @endauth
        });
        @auth
        @if(auth()->user()->can('REMOVE_DISTRIBUTOR'))
        $("#remove_reseller").click(function () {
            var rowcollection =  oTable.$("tr.selected");
            //var user_ids = [];
            var remove_form_builder  = '';
            rowcollection.each(function(index,elem){
                //Do something with 'checkbox_value'
                var user_id = $(this).find(".user_id").val();
                remove_form_builder += '<input type="hidden" name="user_ids[]" value="' + user_id + '">';
            });
            $('<form id="form_remove_reseller" action="{{ route('authorized_reseller.remove') }}" method="post">')
                .append('{{ csrf_field() }}')
                .append(remove_form_builder)
                .append('</form>')
                .appendTo($(document.body)).submit();
        });
        @endif
        @endauth
    });
</script>
@endpush