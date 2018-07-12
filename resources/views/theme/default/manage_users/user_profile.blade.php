@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User Profile
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Manage Users</a></li>
                <li class="active">User Profile</li>
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
                                @cannot('MANAGE_USER_PROFILE_ID', $user->id)
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                                        No Permission Edit User Profile.
                                    </div>
                                @endcannot
                                @can('MANAGE_USER_PROFILE_ID', $user->id)
                                    @if (session('set') == 0 && session('success'))
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    @if (old('update_account') != '' && $errors->count())
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                            User Update Failed.
                                        </div>
                                    @endif
                                    <form action="{{ route('manage_users.user_profile.update', $user->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}

                                        <input type="hidden" name="update_account" value="update_account">

                                        <div class="form-group{{ $errors->has('group') ? ' has-error' : '' }}">
                                            <label for="group" class="col-sm-3 control-label">Group</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="group" name="group"{{ auth()->user()->can('MANAGE_USER_GROUP_ID', $user->id) ? '' : ' disabled' }}>
                                                    @forelse($groups as $group)
                                                        <option value="{{ $group->id }}"{{ $user->group_id == $group->id ? ' selected' : '' }}>{{ $group->name }}</option>
                                                    @empty
                                                        <option value="0" selected></option>
                                                    @endforelse
                                                </select>
                                                @if ($errors->has('group'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('group') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                            <label for="username" class="col-sm-3 control-label">Username</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" placeholder="Username"{{ auth()->user()->can('MANAGE_USER_USERNAME_ID', $user->id) ? '' : ' disabled' }}>
                                                @if ($errors->has('username'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('username') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label for="email" class="col-sm-3 control-label">Email</label>

                                            <div class="col-sm-9">
                                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" placeholder="Email">
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
                                                <input type="text" class="form-control" id="fullname" name="fullname" value="{{ $user->fullname }}" placeholder="Fullname">
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
                                                <input type="text" class="form-control" id="contact" name="contact" value="{{ $user->contact }}" placeholder="Contact No.">
                                                @if ($errors->has('contact'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('contact') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($user->can('MANAGER_USER'))
                                            <div class="form-group">
                                                <label for="distributor" class="col-sm-3 control-label">Distributor</label>

                                                <div class="col-sm-9 checkbox icheck">
                                                    <label>
                                                        <input type="checkbox" id="distributor" name="distributor"{{ $user->distributor ? ' checked' : '' }}>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('max_users') ? ' has-error' : '' }}">
                                                <label for="max_users" class="col-sm-3 control-label">Max Users</label>

                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="max_users" name="max_users" value="{{ $user->max_users }}" placeholder="Max Users">
                                                    @if ($errors->has('max_users'))
                                                        <span class="help-block">
                                                        <strong>{{ $errors->first('max_users') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                            <label for="status" class="col-sm-3 control-label">Status</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="status" name="status">
                                                    @forelse($statuses as $status)
                                                        <option value="{{ $status->id }}"{{ $user->status_id == $status->id ? ' selected' : '' }}>{{ $status->name_set }}</option>
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
                                                <select class="form-control" id="subscription" name="subscription"{{ auth()->user()->can('MANAGE_USER_SUBSCRIPTION_ID', $user->id) ? '' : ' disabled' }}>
                                                    @forelse($subscriptions as $subscription)
                                                        <option value="{{ $subscription->id }}"{{ $user->subscription_id == $subscription->id ? ' selected' : '' }}>{{ $subscription->name }}</option>
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
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->

                    @can('MANAGE_USER_PROFILE_ID', $user->id)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Upload Photo</h3>
                        </div>

                        <div class="panel-body">

                            @if (in_array(session('set'), [1,2]) && session('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (old('update_photo') != '' && $errors->count())
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                    User Photo Update Failed.
                                </div>
                            @endif

                            <form action="{{ route('manage_users.user_profile.upload_photo', $user->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="update_photo" value="update_photo">

                                <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                                    <label for="file" class="col-sm-3 control-label">Image</label>

                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" id="file" name="file" placeholder="Vacation Counter">
                                        @if ($errors->has('file'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('file') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-danger">Upload</button>
                                    </div>
                                </div>

                            </form>

                            <form action="{{ route('manage_users.user_profile.remove_photo', $user->id) }}" method="post" class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" name="empty_photo" value="empty_photo">

                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-danger">Remove Photo</button>
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
        });
    </script>
@endpush