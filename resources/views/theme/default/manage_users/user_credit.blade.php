@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User Credits
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Manage Users</a></li>
                <li class="active">User Credits</li>
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
                                @cannot('UPDATE_USER_CREDIT', $user->id)
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                                        No Permission to Transfer Credit to User.
                                    </div>
                                @endcannot
                                @can('UPDATE_USER_CREDIT', $user->id)

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
                                            Transfer Credit Failed.
                                        </div>
                                    @endif

                                    <form action="{{ route('manage_users.user_credit.transfer_top_up', $user->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <label for="account_credits" class="col-sm-3 control-label">Account Credits :</label>

                                            <div class="col-sm-9">
                                                <p class="form-control-static"><span class="label label-{{ auth()->user()->credits === 'No Limit' ? 'success' : (auth()->user()->credits > 0 ? 'primary' : 'default') }}">{{ auth()->user()->credits }}</span></p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="account_credits" class="col-sm-3 control-label">User Credits :</label>

                                            <div class="col-sm-9">
                                                <p class="form-control-static"><span class="label label-{{ $user->credits === 'No Limit' ? 'success' : ($user->credits > 0 ? 'primary' : 'default') }}">{{ $user->credits }}</span></p>
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('credits') ? ' has-error' : '' }}">
                                            <label for="credits" class="col-sm-3 control-label">Credits</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="credits" name="credits" value="{{ old('credits') }}" placeholder="Credits">
                                                @if ($errors->has('credits'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('credits') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="top_up" class="col-sm-3 control-label">Top-Up</label>

                                            <div class="col-sm-9 checkbox icheck">
                                                <label>
                                                    <input type="checkbox" id="top_up" name="top_up"{{ old('top_up') ? ' checked' : '' }}>
                                                </label>
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