@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User Permission
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Manage Users</a></li>
                <li class="active">User Permission</li>
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
                                @cannot('UPDATE_USER_PERMISSION', $user->id)
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                                        No Permission Edit User Permission.
                                    </div>
                                @endcannot
                                @can('UPDATE_USER_PERMISSION', $user->id)

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
                                            User Permission Update Failed.
                                        </div>
                                    @endif
                                    <form action="{{ route('manage_users.user_permission.update', $user->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}

                                        <!-- Select multiple-->
                                            <div class="form-group{{ $errors->has('permission') ? ' has-error' : '' }}">
                                                <label for="permissions" class="col-sm-3 control-label">Permission</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control select2" multiple="multiple" id="permissions" name="permissions[]" data-placeholder="Select a Permission" style="width: 100%;">
                                                        @foreach($permissions as $permission)
                                                            <option value="{{ $permission->id }}"{{in_array($permission->id, json_decode($user->permissions->pluck('id'))) ? ' selected' : ''}}>{{ $permission->description }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('permission'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('permission') }}</strong>
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