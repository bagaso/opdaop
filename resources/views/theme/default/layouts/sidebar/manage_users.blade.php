<div class="col-md-3">
    @can('CREATE_ACCOUNT')
    <a href="{{ route('manage_users.add_user') }}" class="btn btn-primary btn-block margin-bottom">Create User</a>
    @endcan
    <div class="box box-solid">
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                @can('MANAGE_USER_ALL')
                    <li class="{{ request()->getUri() === route('manage_users.view_all') ? 'active' : '' }}"><a href="{{ route('manage_users.view_all') }}">All Users<span class="label label-primary pull-right">{{ App\User::UserAll(auth()->user())->count() }}</span></a></li>
                @endcan
                @can('MANAGE_USER_SUB_ADMIN')
                    <li class="{{ request()->getUri() === route('manage_users.view_sub_admin') ? 'active' : '' }}"><a href="{{ route('manage_users.view_sub_admin') }}">Sub-Admin<span class="label label-primary pull-right">{{ App\User::SubAdmins(auth()->user())->count() }}</span></a></li>
                @endcan
                @can('MANAGE_USER_RESELLER')
                    <li class="{{ request()->getUri() === route('manage_users.view_reseller') ? 'active' : '' }}"><a href="{{ route('manage_users.view_reseller') }}">Reseller<span class="label label-primary pull-right">{{ App\User::Resellers(auth()->user())->count() }}</span></a></li>
                @endcan
                @can('MANAGE_USER_SUB_RESELLER')
                    <li class="{{ request()->getUri() === route('manage_users.view_sub_reseller') ? 'active' : '' }}"><a href="{{ route('manage_users.view_sub_reseller') }}">Sub-Reseller<span class="label label-primary pull-right">{{ App\User::SubResellers(auth()->user())->count() }}</span></a></li>
                @endcan
                @can('MANAGE_USER_CLIENT')
                    <li class="{{ request()->getUri() === route('manage_users.view_client') ? 'active' : '' }}"><a href="{{ route('manage_users.view_client') }}">Client<span class="label label-primary pull-right">{{ App\User::Clients(auth()->user())->count() }}</span></a></li>
                @endcan
                @if(!auth()->user()->isAdmin() && auth()->user()->can('MANAGE_USER_OTHER'))
                    <li class="{{ request()->getUri() === route('manage_users.view_client') ? 'active' : '' }}"><a href="{{ route('manage_users.view_other') }}">Other User<span class="label label-primary pull-right">{{ App\User::UserOther(auth()->user())->count() }}</span></a></li>
                @endif
                @can('MANAGE_USER_TRASH')
                    <li class="{{ request()->getUri() === route('manage_users.view_trash') ? 'active' : '' }}"><a href="{{ route('manage_users.view_trash') }}">Trash<span class="label label-primary pull-right">{{ App\User::Trashes(auth()->user())->count() }}</span></a></li>
                @endcan
            </ul>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /. box -->
</div>
<!-- /.col -->