<ul class="nav nav-tabs">
    <li class="{{ request()->getUri() === route('manage_users.user_profile', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_profile', $user->id) }}">Account</a></li>
    <li class="{{ request()->getUri() === route('manage_users.user_security', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_security', $user->id) }}">Security</a></li>
    <li class="{{ request()->getUri() === route('manage_users.user_credit', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_credit', $user->id) }}">Credit</a></li>
    <li class="{{ request()->getUri() === route('manage_users.vouchers', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.vouchers', $user->id) }}">Voucher</a></li>
    <li class="{{ request()->getUri() === route('manage_users.user_vacation_mode', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_vacation_mode', $user->id) }}">Freeze Mode</a></li>
    @can('ACCESS_USER_DOWNLINE_ID', $user->id)
    <li class="{{ request()->getUri() === route('manage_users.user_downline', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_downline', $user->id) }}">Downline</a></li>
    @endif
    @can('MANAGE_USER_DURATION_ID', $user->id)
    <li class="{{ request()->getUri() === route('manage_users.user_duration', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_duration', $user->id) }}">Duration</a></li>
    @endcan
    @can('MANAGE_USER_PERMISSION_ID', $user->id)
    <li class="{{ request()->getUri() === route('manage_users.user_permission', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_permission', $user->id) }}">Permission</a></li>
    @endcan
    @can('ACCESS_USER_LOGS_ID', $user->id)
        <li class="{{ request()->getUri() === route('manage_users.user_log', $user->id) ? 'active' : '' }}"><a href="{{ route('manage_users.user_log', $user->id) }}">Logs</a></li>
    @endcan
</ul>