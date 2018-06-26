<div class="col-md-3">
    <a href="{{ route('manage_servers.server_add') }}" class="btn btn-primary btn-block margin-bottom">Add Server</a>
    <div class="box box-solid">
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li class="{{ request()->getUri() === route('manage_servers') ? 'active' : '' }}">
                    <a href="{{ route('manage_servers') }}">
                        Server List
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red">{{ App\Server::where('is_active', 0)->count() }}</small>
                            <small class="label pull-right bg-green" style="margin-right: 3px;">{{ App\Server::where('is_active', 1)->count() }}</small>
                            <small class="label pull-right bg-primary" style="margin-right: 3px;">{{ App\Server::all()->count() }}</small>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /. box -->
</div>
<!-- /.col -->