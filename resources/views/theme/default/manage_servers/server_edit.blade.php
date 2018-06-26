@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Edit Server
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Manage Server</a></li>
                <li class="active">Edit Server</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @cannot('MANAGE_SERVER')
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                            No Permission to Manage Server.
                        </div>
                    </div>
                @endcannot
                @can('MANAGE_SERVER')

                    @include('theme.default.layouts.sidebar.manage_servers')
                    <div class="col-md-9">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Edit Server</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">


                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if (session('error_cloudflare'))
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h4><i class="icon fa fa-ban"></i> Cloudflare Error!</h4>
                                        {{ session('error_cloudflare') }}
                                    </div>
                                @endif
                                @if ($errors->count())
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                        Server Update Failed.
                                    </div>
                                @endif

                                <form action="{{ route('manage_servers.server_edit.update', $server->id) }}" method="post" class="form-horizontal">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('server_type') ? ' has-error' : '' }}">
                                        <label for="server_type" class="col-sm-3 control-label">Server Type</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="server_type" name="server_type">
                                                <option value="VPN"{{ $server->server_type == 'VPN' ? ' selected' : '' }}>VPN</option>
                                                <option value="SS"{{ $server->server_type == 'SS' ? ' selected' : '' }}>Shadow Socks</option>
                                                <option value="SSH"{{ $server->server_type == 'SSH' ? ' selected' : '' }}>SSH</option>
                                            </select>
                                            @if ($errors->has('server_type'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('server_type') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('server_name') ? ' has-error' : '' }}">
                                        <label for="server_name" class="col-sm-3 control-label">Server Name</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="server_name" name="server_name" value="{{ $server->server_name }}" placeholder="Server Name">
                                            @if ($errors->has('server_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('server_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('server_ip') ? ' has-error' : '' }}">
                                        <label for="server_ip" class="col-sm-3 control-label">Server IP</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="server_ip" name="server_ip" value="{{ $server->server_ip }}" placeholder="Server IP">
                                            @if ($errors->has('server_ip'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('server_ip') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('sub_domain') ? ' has-error' : '' }}">
                                        <label for="sub_domain" class="col-sm-3 control-label">Sub-Domain</label>

                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="sub_domain" name="sub_domain" value="{{ $server->sub_domain }}" placeholder="Sub Domain">
                                                <span class="input-group-addon">.{{ request()->getHost() }}</span>
                                            </div>
                                            @if ($errors->has('sub_domain'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('sub_domain') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('server_key') ? ' has-error' : '' }}">
                                        <label for="server_key" class="col-sm-3 control-label">Server Key</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="server_key" name="server_key" value="{{ $server->server_key }}" placeholder="Server Key" readonly>
                                            @if ($errors->has('server_key'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('server_key') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('manager_password') ? ' has-error' : '' }}">
                                        <label for="manager_password" class="col-sm-3 control-label">Manager Password</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="manager_password" name="manager_password" value="{{ $server->manager_password }}" placeholder="Manager Password">
                                            @if ($errors->has('manager_password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('manager_password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('manager_port') ? ' has-error' : '' }}">
                                        <label for="manager_port" class="col-sm-3 control-label">Manager Port</label>

                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="manager_port" name="manager_port" value="{{ $server->manager_port }}" placeholder="Default: 8000">
                                            @if ($errors->has('manager_port'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('manager_port') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('web_port') ? ' has-error' : '' }}">
                                        <label for="web_port" class="col-sm-3 control-label">Web Log Port</label>

                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="web_port" name="web_port" value="{{ $server->web_port }}" placeholder="Default: 80">
                                            @if ($errors->has('web_port'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('web_port') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('download_speed') ? ' has-error' : '' }}">
                                        <label for="download_speed" class="col-sm-3 control-label">Download Speed</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="download_speed" name="download_speed" value="{{ $server->dl_speed }}" placeholder="kbit, mbit">
                                            @if ($errors->has('download_speed'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('download_speed') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('upload_speed') ? ' has-error' : '' }}">
                                        <label for="upload_speed" class="col-sm-3 control-label">Upload Speed</label>

                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="upload_speed" name="upload_speed" value="{{ $server->up_speed }}" placeholder="kbit, mbit">
                                            @if ($errors->has('upload_speed'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('upload_speed') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('data_limit') ? ' has-error' : '' }}">
                                        <label for="data_limit" class="col-sm-3 control-label">Data Limit</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="data_limit" name="data_limit">
                                                <option value="0"{{ $server->limit_bandwidth == 0 ? ' selected' : '' }}>Disable</option>
                                                <option value="1"{{ $server->limit_bandwidth == 1 ? ' selected' : '' }}>Enable</option>
                                            </select>
                                            @if ($errors->has('data_limit'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('data_limit') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label for="status" class="col-sm-3 control-label">Status</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="status" name="status">
                                                <option value="1"{{ $server->is_active == 1 ? ' selected' : '' }}>Up</option>
                                                <option value="0"{{ $server->is_active == 0 ? ' selected' : '' }}>Down</option>
                                            </select>
                                            @if ($errors->has('status'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('status') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('server_access') ? ' has-error' : '' }}">
                                        <label for="server_access" class="col-sm-3 control-label">Access</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="server_access" name="server_access" data-placeholder="Select a Access" style="width: 100%;">
                                                @foreach($server_accesses as $server_access)
                                                    <option value="{{ $server_access->id }}"{{ $server_access->id == $server->server_access_id ? ' selected' : '' }}>{{ $server_access->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('server_access'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('server_access') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('subscription') ? ' has-error' : '' }}">
                                        <label for="subscription" class="col-sm-3 control-label">Subscription</label>
                                        <div class="col-sm-9">
                                            <select class="form-control select2" multiple="multiple" id="subscription" name="subscription[]" data-placeholder="Select a Subscription" style="width: 100%;">
                                                @foreach($subscriptions as $subscription)
                                                    <option value="{{ $subscription->id }}"{{in_array($subscription->id, json_decode($server->subscriptions->pluck('id'))) ? ' selected' : ''}}>{{ $subscription->name }}</option>
                                                @endforeach
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


                            </div>
                            <!-- /.box-body -->
                        </div>
                            <!-- /. box -->
                    </div>
                    <!-- /.col -->

                @endcan
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->
@endsection

@can('MANAGE_SERVER')
    @push('styles')
        <!-- Select2 -->
        <link rel="stylesheet" href="/theme/default/bower_components/select2/dist/css/select2.min.css">
    @endpush

    @push('scripts')
        <!-- Select2 -->
        <script src="/theme/default/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script>
            $(function () {
                //Initialize Select2 Elements
                $('.select2').select2()
            })
        </script>
    @endpush
@endcan