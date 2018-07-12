@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Reload
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Account</a></li>
                <li class="active">Reload</li>
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
                                @elseif(auth()->user()->cannot('ACCOUNT_EXTEND_USING_CREDITS'))
                                <div class="alert alert-warning alert-dismissible">
                                    <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                    No Permission to Extend using Credits.
                                </div>
                                @elseif(auth()->user()->credits === 'No Limit')
                                <div class="alert alert-warning alert-dismissible">
                                    <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                    Account is "No Limit".
                                </div>
                                @elseif(!auth()->user()->isAdmin() && !session('success') && (auth()->user()->freeze_mode))
                                <div class="alert alert-warning alert-dismissible">
                                    <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                    Freeze is Activated
                                </div>
                                @endif
                                @if(auth()->user()->credits !== 'No Limit' && auth()->user()->can('ACCOUNT_EXTEND_USING_CREDITS'))
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
                                    Duration Extend Failed.
                                </div>
                                @endif
                                <form action="{{ route('account.duration.update') }}" method="post" class="form-horizontal">
                                    {{ csrf_field() }}

                                    <div class="form-group">
                                        <label for="account_credits" class="col-sm-3 control-label">Account Credits :</label>

                                        <div class="col-sm-9">
                                            <p class="form-control-static"><span class="label label-{{ auth()->user()->credits_class }}">{{ auth()->user()->credits }}</span></p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="account_credits" class="col-sm-3 control-label">Expired at :</label>

                                        <div class="col-sm-9">
                                            <p class="form-control-static"><span class="label label-{{ auth()->user()->expired_at_class }}">{{ auth()->user()->expired_at }}</span></p>
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('credits') ? ' has-error' : '' }}">
                                        <label for="credits" class="col-sm-3 control-label">Credits</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="credits" name="credits" placeholder="Credits"{{ (auth()->user()->isAdmin() || auth()->user()->freeze_mode || auth()->user()->pause_mode) ? ' disabled' : '' }}>
                                            @if ($errors->has('credits'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('credits') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="submit" class="btn btn-danger"{{ (auth()->user()->isAdmin() || auth()->user()->freeze_mode || auth()->user()->pause_mode) ? ' disabled' : '' }}>Submit</button>
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
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection