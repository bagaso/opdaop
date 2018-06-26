@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Edit Page
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Pages</a></li>
                <li class="active">Edit Page</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @include('theme.default.layouts.sidebar.pages')
                <div class="col-md-9">

                    <div class="box box-default">
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
                                    Page Update Failed.
                                </div>
                            @endif

                            <form action="{{ route('pages.edit.do_edit', $page->id) }}" method="post">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('page_name') ? ' has-error' : '' }}">
                                    <label for="page_name">Page Name</label>
                                    <input type="text" class="form-control" id="page_name" name="page_name" value="{{ $page->name }}" placeholder="Enter page name">
                                    @if ($errors->has('page_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('page_name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                                    <label for="message">HTML Content</label>
                                    <textarea id="html_content" class="textarea" placeholder="Place some text here" name="html_content"
                                              style="width: 100%; height: 350px; resize: none; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ $page->content }}</textarea>
                                    @if ($errors->has('content'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('content') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="public"{{ $page->is_public ? ' checked' : '' }}> Public
                                    </label>
                                </div>

                                <div class="form-group">
                                    <div>
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
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->

@endsection