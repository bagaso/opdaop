<div class="col-md-3">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Application</h3>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                @foreach($json_list as $json)
                    <li class="{{ request()->getUri() == route('app_updates', $json->id) ? 'active' : '' }}"><a href="{{ route('app_updates', $json->id) }}">{{ $json->title }}</a></li>
                @endforeach
            </ul>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /. box -->
</div>
<!-- /.col -->