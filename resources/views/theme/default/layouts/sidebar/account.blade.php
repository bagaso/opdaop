<div class="box-body box-profile">
    <img class="profile-user-img img-responsive img-circle" src="{{ auth()->user()->photo ? 'data:image/jpeg;base64,' . auth()->user()->photo : '/img/default.png' }}" alt="User profile picture">

    <h4 class="text-center">{{ auth()->user()->username }}</h4>

    <p class="text-muted text-center">
        <span class="label label-{{ auth()->user()->group->class }}">{{ auth()->user()->group->name }}</span>
        @can('MANAGE_USER')
            <i class="fa fa-fw fa-check-circle" style="color: #1e8011; text-align: center;"></i>
        @endcan
        @cannot('MANAGE_USER')
            <i class="fa fa-fw fa-times-circle" style="color: #80100c; text-align: center;"></i>
        @endcannot
    </p>

    <ul class="list-group list-group-unbordered">

        <li class="list-group-item">
            <b>Status</b> <a class="pull-right"><span class="label label-{{ auth()->user()->status->class }}">{{ auth()->user()->status->name_get }}</span></a>
        </li>
        <li class="list-group-item">
            <b>Subscription</b> <a class="pull-right"><span class="label label-{{ auth()->user()->subscription->class }}">{{ auth()->user()->subscription->name }}</span></a>
        </li>
        <li class="list-group-item">
            <b>Credits</b> <a class="pull-right"><span class="label label-{{ auth()->user()->credits_class }}">{{ auth()->user()->credits }}</span></a>
        </li>
        <li class="list-group-item">
            <b>Expired at</b> <a class="pull-right"><span class="label label-{{ auth()->user()->expired_at_class }}">{{ auth()->user()->expired_at }}</span></a>
        </li>
        <li class="list-group-item">
            <b>Date Registered</b> <a class="pull-right">{{ auth()->user()->created_at->diffForHumans() }}</a>
        </li>
        <li class="list-group-item">
            <b>Last Update</b> <a class="pull-right">{{ auth()->user()->updated_at ?  auth()->user()->updated_at->diffForHumans() : 'Never' }}</a>
        </li>
        <li class="list-group-item">
            <b>Last Seen</b> <a class="pull-right">{{ \Illuminate\Support\Facades\Request::cookie('lastlogin_datetime') ? \Carbon\Carbon::parse(\Illuminate\Support\Facades\Request::cookie('lastlogin_datetime'))->diffForHumans() : 'Never' }} @ {{ \Illuminate\Support\Facades\Request::cookie('lastlogin_ip') ? \Illuminate\Support\Facades\Request::cookie('lastlogin_ip') : auth()->user()->login_ip }}</a>
        </li>
    </ul>
</div>
<!-- /.box-body -->