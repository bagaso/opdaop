@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Create Post
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">News And Updates</a></li>
                <li class="active">Create Post</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                @can('MANAGER_POST')

                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Create Post</h3>
                                </div>
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
                                            Post Creation Failed.
                                        </div>
                                    @endif

                                    <form role="form" method="post" action="{{ route('news_and_updates.create.do_create') }}">
                                        {{ csrf_field() }}

                                        <div class="box-body">
                                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                                <label for="title">Title</label>
                                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
                                                @if ($errors->has('title'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                            <div class="form-group{{ $errors->has('content_message') ? ' has-error' : '' }}">
                                                <label for="exampleInputPassword1">Content</label>
                                                <textarea class="textarea" placeholder="Place some text here" name="content_message"
                                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                                @if ($errors->has('content_message'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('content_message') }}</strong>
                                            </span>
                                                @endif
                                            </div>

                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="pinned"> Pinned Post
                                                </label>
                                            </div>

                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="public"> Public Post
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

                @cannot('MANAGER_POST')
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <h4><i class="icon fa fa-warning"></i> Access Denied!</h4>
                            No Permission Create Post.
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

@can('MANAGER_POST')
    @push('styles')
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="/theme/default/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    @endpush

    @push('scripts')
        <!-- Bootstrap WYSIHTML5 -->
        <script src="/theme/default/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <script>
            $(function () {
                //bootstrap WYSIHTML5 - text editor
                $('.textarea').wysihtml5()
            })
        </script>
    @endpush
@endcan