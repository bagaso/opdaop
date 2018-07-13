@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User Duration
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Manage Users</a></li>
                <li class="active">User Duration</li>
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
                                @cannot('MANAGE_USER_DURATION_ID', $user->id)
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                                        No Permission Edit User Duration.
                                    </div>
                                @endcannot
                                @can('MANAGE_USER_DURATION_ID', $user->id)

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
                                            User Duration Update Failed.
                                        </div>
                                    @endif

                                    <form action="{{ route('manage_users.user_duration.update', $user->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}

                                        <div class="form-group{{ $errors->has('hours') ? ' has-error' : '' }}">
                                            <label for="hours" class="col-sm-3 control-label">Hours</label>

                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="hours" name="hours" value="{{ old('hours') }}" placeholder="Hours">
                                                @if ($errors->has('hours'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('hours') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('days') ? ' has-error' : '' }}">
                                            <label for="days" class="col-sm-3 control-label">Days</label>

                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="days" name="days" value="{{ old('days') }}" placeholder="Days">
                                                @if ($errors->has('days'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('days') }}</strong>
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
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection