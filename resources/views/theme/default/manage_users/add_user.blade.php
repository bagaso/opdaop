@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Create User
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Manage Users</a></li>
                <li class="active">Create User</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @include('theme.default.layouts.sidebar.manage_users')
                <div class="col-md-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Create User</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            @cannot('CREATE_ACCOUNT')
                                <div class="alert alert-warning">
                                    <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                                    No Permission Create User.
                                </div>
                            @endcannot
                            @can('CREATE_ACCOUNT')

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
                                            Account Creation Failed.
                                        </div>
                                    @endif

                                    <form action="{{ route('manage_users.add_user.create') }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <label for="group" class="col-sm-3 control-label">Group</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="group" name="group">
                                                    @forelse($groups as $group)
                                                        <option value="{{ $group->id }}"{{ old('group') == $group->id ? ' selected' : '' }}>{{ $group->name }}</option>
                                                    @empty
                                                        <option value="0" selected></option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                            <label for="username" class="col-sm-3 control-label">Username</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username')}}" placeholder="Username">
                                                @if ($errors->has('username'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('username') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label for="password" class="col-sm-3 control-label">New Password</label>

                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                            <label for="password_confirmation" class="col-sm-3 control-label">Confirmation Password</label>

                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label for="email" class="col-sm-3 control-label">Email</label>

                                            <div class="col-sm-9">
                                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group{{ $errors->has('fullname') ? ' has-error' : '' }}">
                                            <label for="fullname" class="col-sm-3 control-label">Fullname</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="fullname" name="fullname" value="{{ old('fullname') }}" placeholder="Fullname">
                                                @if ($errors->has('fullname'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('fullname') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }}">
                                            <label for="contact" class="col-sm-3 control-label">Contact No.</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="contact" name="contact" value="{{ old('contact') }}" placeholder="Contact No.">
                                                @if ($errors->has('contact'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('contact') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('max_users') ? ' has-error' : '' }}">
                                            <label for="max_users" class="col-sm-3 control-label">Max Users</label>

                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="max_users" name="max_users" value="{{ old('max_users') }}" placeholder="Max Users">
                                                @if ($errors->has('max_users'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('max_users') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                            <label for="status" class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="status" name="status">
                                                    @forelse($statuses as $status)
                                                        <option value="{{ $status->id }}"{{ old('status') == $status->id ? ' selected' : '' }}>{{ $status->name_set }}</option>
                                                    @empty
                                                        <option value="0" selected></option>
                                                    @endforelse
                                                </select>
                                                @if ($errors->has('status'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('status') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('subscription') ? ' has-error' : '' }}">
                                            <label for="subscription" class="col-sm-3 control-label">Subscription</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="subscription" name="subscription">
                                                    @forelse($subscriptions as $subscription)
                                                        <option value="{{ $subscription->id }}"{{ old('subscription') == $subscription->id ? ' selected' : '' }}>{{ $subscription->name }}</option>
                                                    @empty
                                                        <option value="0" selected></option>
                                                    @endforelse
                                                </select>
                                                @if ($errors->has('subscription'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('subscription') }}</strong>
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
                        <!-- /.box-body -->
                    </div>
                    <!-- /. box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->
@endsection