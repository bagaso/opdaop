@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User Freeze Mode
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Manage Users</a></li>
                <li class="active">User Freeze Mode</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="box box-primary">
                        @include('theme.default.layouts.sidebar.manage_users_profile')
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        @include('theme.default.layouts.menu.manage_users_profile')
                        <div class="tab-content">
                            <div class="active">
                                @cannot('MANAGE_USER_FREEZE_ID', $user->id)
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                                        No Permission to Edit User Freeze Mode.
                                    </div>
                                @endcannot
                                @can('MANAGE_USER_FREEZE_ID', $user->id)

                                    @if(!session('success') && ($user->freeze_mode))
                                        <div class="alert alert-warning alert-dismissible">
                                            <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                            Freeze is Activated
                                        </div>
                                    @elseif($user->expired_at === 'Expired')
                                        <div class="alert alert-warning alert-dismissible">
                                            <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                            Expired User Cannot Activate Vacation Mode.
                                        </div>
                                    @elseif(!session('success') && auth()->user()->cannot('BYPASS_USER_FREEZE_LIMIT_ID', $user->id) && !$user->freeze_mode && $user->freeze_ctr < 1)
                                        <div class="alert alert-warning alert-dismissible">
                                            <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                            Cannot Activate Freeze Mode Limit has been reach.
                                        </div>
                                    @endif
                                    @if (session('set') == 0 && session('success'))
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    @if (old('freeze_account') != '' && $errors->count())
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                            Vacation Mode Update Failed.
                                        </div>
                                    @endif
                                    <form action="{{ route('manage_users.user_vacation_mode.enable', $user->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="freeze_account" value="freeze_account">

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Vacation Left :</label>

                                            <div class="col-sm-9">
                                                <p class="form-control-static">{{ $user->freeze_ctr }} (*Reset every 1st day of the month)</p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Freeze Mode :</label>

                                            <div class="col-sm-9">
                                                <p class="form-control-static"><span class="label label-{{ $user->freeze_mode ? 'success' : 'danger' }}">{{ $user->freeze_mode ? 'Enabled' : 'Disabled' }}</span></p>
                                            </div>
                                        </div>

                                        @if (!$user->freeze_mode)
                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-9">
                                                    <button type="submit" class="btn btn-danger"{{ ($user->expired_at === 'Expired' || (auth()->user()->cannot('BYPASS_USER_FREEZE_LIMIT_ID', $user->id) && $user->freeze_ctr < 1)) ? ' disabled' : '' }}>Submit</button>
                                                </div>
                                            </div>
                                        @endif
                                    </form>

                                    @if ($user->freeze_mode)
                                        <form action="{{ route('manage_users.user_vacation_mode.disable', $user->id) }}" method="post" class="form-horizontal">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-9">
                                                    <button type="submit" class="btn btn-danger">Disable Freeze</button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif

                                @endcan

                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->

                    @can('OWNER')
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Freeze Counter</h3>
                        </div>

                        <div class="panel-body">

                            @if (session('set') == 1 && session('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (old('edit_counter') != '' && $errors->count())
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                    User Vacation Counter Update Failed.
                                </div>
                            @endif

                                <form action="{{ route('manage_users.user_vacation_mode.counter_edit', $user->id) }}" method="post" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="edit_counter" value="edit_counter">

                                    <div class="form-group{{ $errors->has('vacation_counter') ? ' has-error' : '' }}">
                                        <label for="vacation_counter" class="col-sm-3 control-label">Vacation Counter</label>

                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="vacation_counter" name="vacation_counter" placeholder="Vacation Counter">
                                            @if ($errors->has('vacation_counter'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('vacation_counter') }}</strong>
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

                        </div>
                    </div>
                    @endcan

                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection

@can('MANAGE_USER_FREEZE_ID', $user->id)
    @push('styles')
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="/theme/default/plugins/iCheck/all.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="/theme/default/plugins/iCheck/square/blue.css">
    @endpush

    @push('scripts')
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
@endcan