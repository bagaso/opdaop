@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Full Credit Logs
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Full Credit Logs</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body table-responsive">
                            <table class="table table-hover" id="full_credit_logs-table" style="font-size: small">
                                <thead>
                                <tr>
                                    <th>From</th>
                                    <th>Credit Before</th>
                                    <th>Credit After</th>
                                    <th>To</th>
                                    <th>Credit Before</th>
                                    <th>Credit After</th>
                                    <th>Type</th>
                                    <th>Credit</th>
                                    <th>Duration</th>
                                    <th>DateTime</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->

    </div>

    <!-- /.content-wrapper -->
@endsection

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
            var oTable =  $('#full_credit_logs-table').DataTable({
                order: [ 9, 'desc' ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('logs.credit.list') }}',
                    method: 'POST'
                },
                columns: [
                    { data: 'user_from', name: 'user_from.username' },
                    { data: 'credit_before_from', name: 'credit_before_from' },
                    { data: 'credit_after_from', name: 'credit_after_from' },
                    { data: 'user_to', name: 'user_to.username' },
                    { data: 'credit_before_to', name: 'credit_before_to' },
                    { data: 'credit_after_to', name: 'credit_after_to' },
                    { data: 'type', name: 'type' },
                    { data: 'credit_used', name: 'credit_used' },
                    { data: 'duration', name: 'duration' },
                    { data: 'created_at', name: 'created_at' }
                ]
            });
        });
    </script>
@endpush