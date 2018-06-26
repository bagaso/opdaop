<div class="col-md-3">
    <a href="{{ route('pages.add') }}" class="btn btn-primary btn-block margin-bottom">Create Page</a>
    <div class="box box-solid">
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li class="{{ request()->getUri() === route('pages.system_page') ? 'active' : '' }}"><a href="{{ route('pages.system_page') }}">System Page</a></li>
                <li class="{{ request()->getUri() === route('pages') ? 'active' : '' }}"><a href="{{ route('pages') }}">Custom Pages</a></li>
            </ul>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /. box -->
</div>
<!-- /.col -->