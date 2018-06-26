@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Quick Transfer Credits
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Account</a></li>
                <li class="active">Quick Transfer Credits</li>
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
                                @elseif(auth()->user()->cannot('TRANSFER_CREDIT'))
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                        No Permission to Transfer Credit.
                                    </div>
                                @endif
                                @if(auth()->user()->can('TRANSFER_CREDIT'))
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
                                    <form action="{{ route('account.transfer_credits.update') }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <label for="account_credits" class="col-sm-3 control-label">Account Credits :</label>

                                            <div class="col-sm-9">
                                                <p class="form-control-static"><span class="label label-{{ auth()->user()->credits_class }}">{{ auth()->user()->credits }}</span></p>
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                            <label for="username" class="col-sm-3 control-label">Transfer To</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="{{ old('username') }}">
                                                @if ($errors->has('username'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('username') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('credits') ? ' has-error' : '' }}">
                                            <label for="credits" class="col-sm-3 control-label">Amount</label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="credits" name="credits" placeholder="Credits">
                                                @if ($errors->has('credits'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('credits') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button type="submit" class="btn btn-danger">Transfer</button>
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