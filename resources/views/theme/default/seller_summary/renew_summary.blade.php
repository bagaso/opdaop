@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Seller Monitoring
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Seller Monitoring</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body table-responsive">
                            <table class="table table-hover" id="resellers-table" style="font-size: small">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Group</th>
                                        <th>Upline</th>
                                        <th>Credit Accumulated</th>
                                        <th>Date 1st Applied Credit</th>
                                        <th>Status</th>
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
            var oTable =  $('#resellers-table').DataTable({
                order: [ 0, 'desc' ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('seller_summary.renew_summary_list') }}',
                    method: 'POST'
                },
                columns: [
                    { data: 'username', name: 'username' },
                    { data: 'group', name: 'group.name' },
                    { data: 'upline', name: 'upline' },
                    { data: 'credit_accumulated', name: 'credit_accumulated' },
                    { data: 'seller_first_applied_credit', name: 'seller_first_applied_credit' },
                    { data: 'status', name: 'status' },
                ]
            });
        });
    </script>
@endpush