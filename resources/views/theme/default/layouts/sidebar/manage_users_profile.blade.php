<div class="box-body box-profile">
    <img class="profile-user-img img-responsive img-circle" src="{{ $user->photo ? 'data:image/jpeg;base64,' . $user->photo : '/img/default.png' }}" alt="User profile picture">

    <h4 class="text-center">{{ $user->username }}</h4>

    <p class="text-muted text-center"><span class="label label-{{ $user->group->class }}">{{ $user->group->name }}</span></p>

    <ul class="list-group list-group-unbordered">
        <li class="list-group-item">
            <b>Username</b> <a class="pull-right">{{ $user->username }}</a>
        </li>
        <li class="list-group-item">
            <b>Status</b> <a class="pull-right"><span class="label label-{{ $user->status->class }}">{{ $user->status->name_get }}</span></a>
        </li>
        <li class="list-group-item">
            <b>Subscription</b> <a class="pull-right"><span class="label label-{{ $user->subscription->class }}">{{ $user->subscription->name }}</span></a>
        </li>
        <li class="list-group-item">
            <b>Credits</b> <a class="pull-right"><span class="label label-{{ $user->credits_class }}">{{ $user->credits }}</span></a>
        </li>
        <li class="list-group-item">
            <b>Expired at</b> <a class="pull-right"><span class="label label-{{ $user->expired_at_class }}">{{ $user->expired_at }}</span></a>
        </li>
        <li class="list-group-item">
            <b>Date Registered</b> <a class="pull-right">{{ $user->created_at->diffForHumans() }}</a>
        </li>
        <li class="list-group-item">
            <b>Last Update</b> <a class="pull-right">{{ $user->updated_at->diffForHumans() }}</a>
        </li>
        <li class="list-group-item">
            <b>Last Seen</b> <a class="pull-right">{{ $user->login_datetime ? $user->login_datetime->diffForHumans()  : 'Never' }} @ {{ $user->login_ip }}</a>
        </li>
    </ul>

</div>
<!-- /.box-body -->