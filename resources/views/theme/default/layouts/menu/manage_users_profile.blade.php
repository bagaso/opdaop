<ul class="nav nav-tabs">
    <li class="{{ request()->getUri() === route('manage_users.user_profile', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_profile', $user->id) }}">Account</a></li>
    <li class="{{ request()->getUri() === route('manage_users.user_security', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_security', $user->id) }}">Security</a></li>
    <li class="{{ request()->getUri() === route('manage_users.user_credit', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_credit', $user->id) }}">Credit</a></li>
    <li class="{{ request()->getUri() === route('manage_users.vouchers', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.vouchers', $user->id) }}">Voucher</a></li>
    <li class="{{ request()->getUri() === route('manage_users.user_vacation_mode', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_vacation_mode', $user->id) }}">Freeze Mode</a></li>
    @if(auth()->user()->can('ACCESS_USER_DOWNLINE', $user->id) && auth()->user()->can('MANAGE_USER_OTHER'))
    <li class="{{ request()->getUri() === route('manage_users.user_downline', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_downline', $user->id) }}">Downline</a></li>
    @endif
    @can('UPDATE_USER_DURATION', $user->id)
    <li class="{{ request()->getUri() === route('manage_users.user_duration', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_duration', $user->id) }}">Duration</a></li>
    @endcan
    @can('UPDATE_USER_PERMISSION', $user->id)
    <li class="{{ request()->getUri() === route('manage_users.user_permission', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_permission', $user->id) }}">Permission</a></li>
    @endcan
    @can('ACCESS_USER_LOGS', $user->id)
        <li class="{{ request()->getUri() === route('manage_users.user_log', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_log', $user->id) }}">Logs</a></li>
    @endcan
</ul>