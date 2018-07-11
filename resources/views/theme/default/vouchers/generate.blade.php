@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Generate Voucher
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Vouchers</a></li>
                <li class="active">Generate Voucher</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Generate Voucher</h3>
                        </div>
                        <div class="panel-body">

                            @can('MANAGE_VOUCHER')
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                                    {{ session('success') }}
                                    @if (session('vouchers'))
                                        <div class="box-body">
                                            <ul>
                                                @foreach(explode('|', session('vouchers')) as $voucher)
                                                    @if($voucher)
                                                        <li>{{$voucher}}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if ($errors->count())
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                    Generate Voucher Failed.
                                </div>
                            @endif
                            <form action="{{ route('vouchers.generate') }}" method="post" class="form-horizontal">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label for="account_credits" class="col-sm-3 control-label">Account Credits :</label>

                                    <div class="col-sm-9">
                                        <p class="form-control-static"><span class="label label-{{ auth()->user()->credits_class }}">{{ auth()->user()->credits }}</span></p>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('credit') ? ' has-error' : '' }}">
                                    <label for="credit" class="col-sm-3 control-label">Credit</label>

                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="credit" name="credit" placeholder="1 Credit = Voucher Code">
                                        @if ($errors->has('credit'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('credit') }}</strong>
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

                            @cannot('MANAGE_VOUCHER')
                            <div class="alert alert-warning alert-dismissible">
                                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                No Permission to Generate Voucher.
                            </div>
                            @endcannot

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Generated Voucher List</h3>
                        </div>
                        <div class="panel-body table-responsive">

                            <table class="table table-bordered table-hover" id="vouchers-table">
                                <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Duration</th>
                                    <th>Created By</th>
                                    <th>Used By</th>
                                    <th>Date Used</th>
                                    <th>Date Created</th>
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
            $('#vouchers-table').DataTable({
                order: [ 5, 'desc' ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('vouchers.generate.list') }}',
                    method: 'POST'
                },
                columns: [
                    { data: 'code', name: 'code' },
                    { data: 'duration', name: 'duration' },
                    { data: 'user_from', name: 'user_from.username' },
                    { data: 'user_to', name: 'user_to.username' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'created_at', name: 'created_at' }
                ]
            });
        });
    </script>
@endpush