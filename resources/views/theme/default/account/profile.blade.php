@extends('theme.default.layouts.panel')

@section('panel_content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            My Profile
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Account</a></li>
            <li class="active">My Profile</li>
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
                            @if(auth()->user()->isActive())
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
                                    Account Update Failed.
                                </div>
                            @endif
                            <form action="{{ route('account.profile.update') }}" method="post" class="form-horizontal">
                                {{ csrf_field() }}

                                <input type="hidden" name="update_account" value="update_account">

                                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                    <label for="username" class="col-sm-3 control-label">Username</label>

                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="username" name="username" value="{{ auth()->user()->username }}" placeholder="Username"{{ auth()->user()->can('UPDATE_USERNAME') ? '' : ' disabled' }}>
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
                                        <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" placeholder="Email">
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
                                        <input type="text" class="form-control" id="fullname" name="fullname" value="{{ auth()->user()->fullname }}" placeholder="Fullname">
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
                                        <input type="text" class="form-control" id="contact" name="contact" value="{{ auth()->user()->contact }}" placeholder="Contact No.">
                                        @if ($errors->has('contact'))
                                            <span class="help-block">
                                                    <strong>{{ $errors->first('contact') }}</strong>
                                                </span>
                                        @endif
                                    </div>
                                </div>
                                @if(!auth()->user()->isAdmin())
                                    <div class="form-group">
                                        <label for="distributor" class="col-sm-3 control-label">Distributor</label>

                                        <div class="col-sm-9 checkbox icheck">
                                            <label>
                                                <input type="checkbox" id="distributor" name="distributor"{{ auth()->user()->distributor ? ' checked' : '' }}>
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-danger">Submit</button>
                                    </div>
                                </div>
                            </form>
                            @endif
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->

                @can('UPDATE_ACCOUNT')
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
                                Photo Update Failed.
                            </div>
                        @endif

                        <form action="{{ route('account.profile.upload_photo') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
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

                            <form action="{{ route('account.profile.remove_photo') }}" method="post" class="form-horizontal">
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