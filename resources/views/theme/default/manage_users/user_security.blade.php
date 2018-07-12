@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User Security
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Manage Users</a></li>
                <li class="active">User Security</li>
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

                                @cannot('UPDATE_USER_SECURITY', $user->id)
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                                        No Permission Edit User Security.
                                    </div>
                                @endcannot
                                @can('UPDATE_USER_SECURITY', $user->id)

                                    @if (session('set') == 0 && session('success'))
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    @if (old('update_security') != '' &&  $errors->count())
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                            User Security Update Failed.
                                        </div>
                                    @endif

                                    <form action="{{ route('manage_users.user_security', $user->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}

                                        <input type="hidden" name="update_security" value="update_security">

                                        <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                                            <label for="new_password" class="col-sm-3 control-label">New Password</label>

                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password">
                                                @if ($errors->has('new_password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('new_password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                                            <label for="new_password_confirmation" class="col-sm-3 control-label">Confirmation Password</label>

                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm Password">
                                                @if ($errors->has('new_password_confirmation'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('new_password_confirmation') }}</strong>
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

                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->

                    @can('UPDATE_USER_SECURITY', $user->id)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Service Password</h3>
                        </div>

                        <div class="panel-body">

                            @if (session('set') == 1 && session('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (old('update_service_password') != '' && $errors->count())
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                    Service Password Update Failed.
                                </div>
                            @endif

                            <form action="{{ route('manage_users.user_security.update.service_password', $user->id) }}" method="post" class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" name="update_service_password" value="update_service_password">

                                <div class="form-group">
                                    <label for="account_credits" class="col-sm-3 control-label">Current Service Password :</label>

                                    <div class="col-sm-9">
                                        <p class="form-control-static">{{ $user->service_password ? $user->service_password : 'Not Set' }}</p>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('service_password') ? ' has-error' : '' }}">
                                    <label for="service_password" class="col-sm-3 control-label">Service Password</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="" name="service_password" placeholder="Service Password">
                                        @if ($errors->has('service_password'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('service_password') }}</strong>
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