@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Site Settings
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Site Settings</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @cannot('MANAGE_SITE_SETTINGS')
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                            No Permission to Edit Site Settings
                        </div>
                    </div>
                @endcannot
                @can('MANAGE_SITE_SETTINGS')

                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Edit Settings</h3>
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
                                    @if ($errors->count())
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                            Settings Update Failed.
                                        </div>
                                    @endif

                                    <form action="{{ route('settings.update') }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}

                                        <div class="form-group{{ $errors->has('site_name') ? ' has-error' : '' }}">
                                            <label for="site_name" class="col-sm-3 control-label">Site Name</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="site_name" name="site_name" value="{{ $settings->site_name }}" placeholder="Site Name">
                                                @if ($errors->has('site_name'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('site_name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('site_url') ? ' has-error' : '' }}">
                                            <label for="site_url" class="col-sm-3 control-label">Site URL</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="site_url" name="site_url" value="{{ $settings->site_url }}" placeholder="Site URL">
                                                @if ($errors->has('site_url'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('site_name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('site_maintenance_mode') ? ' has-error' : '' }}">
                                            <label for="site_maintenance_mode" class="col-sm-3 control-label">Site Maintenance Mode</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="site_maintenance_mode" name="site_maintenance_mode">
                                                    <option value="1"{{ old('site_maintenance_mode') ? (old('site_maintenance_mode') == 1 ? 'selected' : '') : ($settings->maintenance_mode ? ' selected' : '') }}>{{ $settings->maintenance_mode ? 'Enabled' : 'Enable' }}</option>
                                                    <option value="0"{{ old('site_maintenance_mode') ? (old('site_maintenance_mode') == 0 ? 'selected' : '') : (!$settings->maintenance_mode ? ' selected' : '') }}>{{ !$settings->maintenance_mode ? 'Disabled' : 'Disable' }}</option>
                                                </select>
                                                @if ($errors->has('site_maintenance_mode'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('site_maintenance_mode') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('backup') ? ' has-error' : '' }}">
                                            <label for="backup" class="col-sm-3 control-label">Backup</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="backup" name="backup">
                                                    <option value="1"{{ old('backup') ? (old('backup') == 1 ? 'selected' : '') : ($settings->enable_backup ? ' selected' : '') }}>{{ $settings->enable_backup ? 'Enabled' : 'Enable' }}</option>
                                                    <option value="0"{{ old('backup') ? (old('backup') == 0 ? 'selected' : '') : (!$settings->enable_backup ? ' selected' : '') }}>{{ !$settings->enable_backup ? 'Disabled' : 'Disable' }}</option>
                                                </select>
                                                @if ($errors->has('backup'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('backup') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('backup_cron') ? ' has-error' : '' }}">
                                            <label for="backup_cron" class="col-sm-3 control-label">Backup Cron</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="backup_cron" name="backup_cron" value="{{ $settings->backup_cron }}" placeholder="Cron Format">
                                                @if ($errors->has('backup_cron'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('backup_cron') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('trial_period') ? ' has-error' : '' }}">
                                            <label for="trial_period" class="col-sm-3 control-label">Trial Period</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="trial_period" name="trial_period" value="{{ $settings->trial_period }}" placeholder="1h = 1hour, 1d = 1day">
                                                @if ($errors->has('trial_period'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('trial_period') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('data_reset') ? ' has-error' : '' }}">
                                            <label for="data_reset" class="col-sm-3 control-label">Data Reset</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="data_reset" name="data_reset">
                                                    <option value="1"{{ old('data_reset') ? (old('data_reset') == 1 ? 'selected' : '') : ($settings->enable_data_reset ? ' selected' : '') }}>{{ $settings->enable_data_reset ? 'Enabled' : 'Enable' }}</option>
                                                    <option value="0"{{ old('data_reset') ? (old('data_reset') == 0 ? 'selected' : '') : (!$settings->enable_data_reset ? ' selected' : '') }}>{{ !$settings->enable_data_reset ? 'Disabled' : 'Disable' }}</option>
                                                </select>
                                                @if ($errors->has('data_reset'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('data_reset') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('data_reset_cron') ? ' has-error' : '' }}">
                                            <label for="data_reset_cron" class="col-sm-3 control-label">Data Reset Cron</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="data_reset_cron" name="data_reset_cron" value="{{ $settings->data_reset_cron }}" placeholder="Cron Format">
                                                @if ($errors->has('data_reset_cron'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('data_reset_cron') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('data_allowance') ? ' has-error' : '' }}">
                                            <label for="data_allowance" class="col-sm-3 control-label">Data Allowance</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="data_allowance" name="data_allowance" value="{{ $settings->data_allowance }}" placeholder="1mb, 100mb, 1gb">
                                                @if ($errors->has('data_allowance'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('data_allowance') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('enable_authorized_reseller') ? ' has-error' : '' }}">
                                            <label for="enable_authorized_reseller" class="col-sm-3 control-label">Enable Authorized Reseller</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="enable_authorized_reseller" name="enable_authorized_reseller">
                                                    <option value="1"{{ old('enable_authorized_reseller') ? (old('enable_authorized_reseller') == 1 ? 'selected' : '') : ($settings->enable_authorized_reseller ? ' selected' : '') }}>{{ $settings->enable_authorized_reseller ? 'Enabled' : 'Enable' }}</option>
                                                    <option value="0"{{ old('enable_authorized_reseller') ? (old('enable_authorized_reseller') == 0 ? 'selected' : '') : (!$settings->enable_authorized_reseller ? ' selected' : '') }}>{{ !$settings->enable_authorized_reseller ? 'Disabled' : 'Disable' }}</option>
                                                </select>
                                                @if ($errors->has('enable_authorized_reseller'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('enable_authorized_reseller') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('public_authorized_reseller') ? ' has-error' : '' }}">
                                            <label for="public_authorized_reseller" class="col-sm-3 control-label">Public Authorized Reseller</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="public_authorized_reseller" name="public_authorized_reseller">
                                                    <option value="1"{{ old('public_authorized_reseller') ? (old('public_authorized_reseller') == 1 ? 'selected' : '') : ($settings->public_authorized_reseller ? ' selected' : '') }}>{{ $settings->public_authorized_reseller ? 'Enabled' : 'Enable' }}</option>
                                                    <option value="0"{{ old('public_authorized_reseller') ? (old('public_authorized_reseller') == 0 ? 'selected' : '') : (!$settings->public_authorized_reseller ? ' selected' : '') }}>{{ !$settings->public_authorized_reseller ? 'Disabled' : 'Disable' }}</option>
                                                </select>
                                                @if ($errors->has('public_authorized_reseller'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('public_authorized_reseller') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('enable_server_status') ? ' has-error' : '' }}">
                                            <label for="enable_server_status" class="col-sm-3 control-label">Enable Server Status</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="enable_server_status" name="enable_server_status">
                                                    <option value="1"{{ old('enable_server_status') ? (old('enable_server_status') == 1 ? 'selected' : '') : ($settings->enable_server_status ? ' selected' : '') }}>{{ $settings->enable_server_status ? 'Enabled' : 'Enable' }}</option>
                                                    <option value="0"{{ old('enable_server_status') ? (old('enable_server_status') == 0 ? 'selected' : '') : (!$settings->enable_server_status ? ' selected' : '') }}>{{ !$settings->enable_server_status ? 'Disabled' : 'Disable' }}</option>
                                                </select>
                                                @if ($errors->has('enable_server_status'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('enable_server_status') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('public_server_status') ? ' has-error' : '' }}">
                                            <label for="public_server_status" class="col-sm-3 control-label">Public Server Status</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="public_server_status" name="public_server_status">
                                                    <option value="1"{{ old('public_server_status') ? (old('public_server_status') == 1 ? 'selected' : '') : ($settings->public_server_status ? ' selected' : '') }}>{{ $settings->public_server_status ? 'Enabled' : 'Enable' }}</option>
                                                    <option value="0"{{ old('public_server_status') ? (old('public_server_status') == 0 ? 'selected' : '') : (!$settings->public_server_status ? ' selected' : '') }}>{{ !$settings->public_server_status ? 'Disabled' : 'Disable' }}</option>
                                                </select>
                                                @if ($errors->has('public_server_status'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('public_server_status') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('enable_online_users') ? ' has-error' : '' }}">
                                            <label for="enable_online_users" class="col-sm-3 control-label">Enable Online Users</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="enable_online_users" name="enable_online_users">
                                                    <option value="1"{{ old('enable_online_users') ? (old('enable_online_users') == 1 ? 'selected' : '') : ($settings->enable_online_users ? ' selected' : '') }}>{{ $settings->enable_online_users ? 'Enabled' : 'Enable' }}</option>
                                                    <option value="0"{{ old('enable_online_users') ? (old('enable_online_users') == 0 ? 'selected' : '') : (!$settings->enable_online_users ? ' selected' : '') }}>{{ !$settings->enable_online_users ? 'Disabled' : 'Disable' }}</option>
                                                </select>
                                                @if ($errors->has('enable_online_users'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('enable_online_users') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('public_online_users') ? ' has-error' : '' }}">
                                            <label for="public_online_users" class="col-sm-3 control-label">Public Online Users</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="public_online_users" name="public_online_users">
                                                    <option value="1"{{ old('public_online_users') ? (old('public_online_users') == 1 ? 'selected' : '') : ($settings->public_online_users ? ' selected' : '') }}>{{ $settings->public_online_users ? 'Enabled' : 'Enable' }}</option>
                                                    <option value="0"{{ old('public_online_users') ? (old('public_online_users') == 0 ? 'selected' : '') : (!$settings->public_online_users ? ' selected' : '') }}>{{ !$settings->public_online_users ? 'Disabled' : 'Disable' }}</option>
                                                </select>
                                                @if ($errors->has('public_online_users'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('public_online_users') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('max_credit_transfer') ? ' has-error' : '' }}">
                                            <label for="max_credit_transfer" class="col-sm-3 control-label">Max Credit Transfer</label>

                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="max_credit_transfer" name="max_credit_transfer" value="{{ $settings->max_credit_transfer }}" placeholder="1, 10, 100">
                                                @if ($errors->has('max_credit_transfer'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('max_credit_transfer') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('renewal_qualified') ? ' has-error' : '' }}">
                                            <label for="renewal_qualified" class="col-sm-3 control-label">Credit Renewal Qualified</label>

                                            <div class="col-sm-9">
                                                <input type="number" class="form-control" id="renewal_qualified" name="renewal_qualified" value="{{ $settings->renewal_qualified }}" placeholder="1, 10, 100">
                                                @if ($errors->has('renewal_qualified'))
                                                    <span class="help-block">
                                                    <strong>{{ $errors->first('renewal_qualified') }}</strong>
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

@can('MANAGE_SITE_SETTINGS')
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