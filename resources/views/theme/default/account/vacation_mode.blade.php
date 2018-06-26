@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Free Mode
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Account</a></li>
                <li class="active">Freeze Mode</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="box box-primary">
                        @include('theme.default.layouts.sidebar.account')
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        @include('theme.default.layouts.menu.account')
                        <div class="tab-content">
                            <div class="active">
                                @if(!auth()->user()->isActive())
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                        Account is Inactive.
                                    </div>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                        Account is "No Limit".
                                    </div>
                                @endif
                                @if(!auth()->user()->isAdmin() && auth()->user()->isActive())
                                @if(!session('success') && (auth()->user()->freeze_mode))
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                        Freeze is Activated
                                    </div>
                                @elseif(auth()->user()->expired_at === 'Expired')
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                        Expired Account Cannot Activate Vacation Mode.
                                    </div>
                                @elseif(!session('success') && auth()->user()->cannot('BYPASS_FREEZE_LIMIT') && auth()->user()->freeze_ctr < 1)
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                        Cannot Activate Freeze Mode Limit has been reach.
                                    </div>
                                @endif
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
                                        Vacation Mode Update Failed.
                                    </div>
                                @endif
                                <form action="{{ route('account.vacation.enable') }}" method="post" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Vacation Counter :</label>

                                        <div class="col-sm-9">
                                            <p class="form-control-static">{{ auth()->user()->freeze_ctr }}  (* Reset every 1st day of the month)</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Freeze Mode :</label>

                                        <div class="col-sm-9">
                                            <p class="form-control-static"><span class="label label-{{ auth()->user()->freeze_mode ? 'success' : 'danger' }}">{{ auth()->user()->freeze_mode ? 'Enabled' : 'Disabled' }}</span></p>
                                        </div>
                                    </div>

                                    @if (!auth()->user()->freeze_mode)
                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button type="submit" class="btn btn-danger"{{ (auth()->user()->expired_at === 'Expired' || (auth()->user()->cannot('BYPASS_FREEZE_LIMIT') && auth()->user()->freeze_ctr < 1)) ? ' disabled' : '' }}>Submit</button>
                                            </div>
                                        </div>
                                    @endif
                                </form>
                                @if (!auth()->user()->isAdmin() && auth()->user()->freeze_mode)
                                    <form action="{{ route('account.vacation.disable') }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button type="submit" class="btn btn-danger">Disable Freeze</button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                                @endif
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
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
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="/theme/default/plugins/iCheck/all.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/theme/default/plugins/iCheck/square/blue.css">
@endpush

@push('scripts')
    <!-- bootstrap datepicker -->
    <script src="/theme/default/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <!-- iCheck -->
    <script src="/theme/default/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '15%' // optional
            });
            //Date picker
            $('#datepicker').datepicker({
                autoclose: true
            })
        });
    </script>
@endpush