@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Create Json File
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Json File</a></li>
                <li class="active">Create Json</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    @can('CREATE_JSON_FILE')
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
                                    Json Creation Failed.
                                </div>
                            @endif

                            <form role="form" method="post" action="{{ route('json.create.do_create') }}">
                                {{ csrf_field() }}

                                <div class="box-body">

                                    <div class="form-group{{ $errors->has('json_name') ? ' has-error' : '' }}">
                                        <label for="json_name">Name</label>
                                        <input type="text" class="form-control" id="json_name" name="json_name" placeholder="Enter Json Name" value="{{ old('json_name') }}">
                                        @if ($errors->has('json_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('json_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('version') ? ' has-error' : '' }}">
                                        <label for="version">Version</label>
                                        <input type="text" class="form-control" id="version" name="version" placeholder="Enter Version" value="{{ old('version') }}">
                                        @if ($errors->has('version'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('version') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group{{ $errors->has('json_data') ? ' has-error' : '' }}">
                                        <label for="json_data">Json Data</label>
                                        <textarea class="textarea" placeholder="Place some valid json data here" name="json_data"
                                                  style="width: 100%; height: 350px; resize: none; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('json_data') }}</textarea>
                                        @if ($errors->has('json_data'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('json_data') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="is_enable"{{ old('is_enable') ? ' checked' : '' }}> Enable
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
                    @endcan
                    @cannot('CREATE_JSON_FILE')
                        <div class="alert alert-warning alert-dismissible">
                            <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                            No Permission to Create Json File.
                        </div>
                    @endcannot
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection