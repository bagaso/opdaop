@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Edit Json File
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Json File</a></li>
                <li class="active">Edit Json File</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                @can('MANAGE_UPDATE_JSON')
                @include('theme.default.layouts.sidebar.json_view_edit')
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-body">

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
                                    Json Update Failed.
                                </div>
                            @endif

                            <form role="form" method="post" action="{{ route('json.edit.do_edit', $json->id) }}">
                                {{ csrf_field() }}

                                <div class="box-body">

                                    <div class="form-group{{ $errors->has('json_name') ? ' has-error' : '' }}">
                                        <label for="json_name">Name</label>
                                        <input type="text" class="form-control" id="json_name" name="json_name" placeholder="Enter Json Name" value="{{ $json->name }}">
                                        @if ($errors->has('json_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('json_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('version') ? ' has-error' : '' }}">
                                        <label for="version">Version</label>
                                        <input type="text" class="form-control" id="version" name="version" placeholder="Enter Version" value="{{ $json->version }}">
                                        @if ($errors->has('version'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('version') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('json_data') ? ' has-error' : '' }}">
                                        <label for="json_data">Json Data</label>
                                        <textarea class="textarea" placeholder="Place some valid json data here" name="json_data"
                                                  style="width: 100%; height: 350px; resize: none; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ $json->json_data }}</textarea>
                                        @if ($errors->has('json_data'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('json_data') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="is_enable"{{ $json->is_enable ? ' checked' : '' }}> Enable
                                        </label>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <!-- /.col -->
                @endcan
                @cannot('MANAGE_UPDATE_JSON')
                <div class="col-md-12">
                    <div class="alert alert-warning alert-dismissible">
                        <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                        No Permission to Edit Json File.
                    </div>
                </div>
                @endcannot
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection