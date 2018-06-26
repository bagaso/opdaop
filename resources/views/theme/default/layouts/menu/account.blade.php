<ul class="nav nav-tabs">
    <li class="{{ request()->getUri() === route('account.profile') ? 'active' : '' }}"><a href="{{ route('account.profile') }}">Account</a></li>
    <li class="{{ request()->getUri() === route('account.security') ? 'active' : '' }}"><a href="{{ route('account.security') }}">Security</a></li>
    <li class="{{ request()->getUri() === route('account.duration')  ? 'active' : '' }}"><a href="{{ route('account.duration') }}">Reload</a></li>
    @if(auth()->user()->isAdmin() || in_array(auth()->user()->group->id, [2,3,4]))
    <li class="{{ request()->getUri() === route('account.transfer_credits') ? 'active' : '' }}"><a href="{{ route('account.transfer_credits') }}">Quick Transfer</a></li>
    @endif
    <li class="{{ request()->getUri() === route('account.vacation') ? 'active' : '' }}"><a href="{{ route('account.vacation') }}">Freeze Mode</a></li>
    <li class="{{ request()->getUri() === route('account.credit_logs') ? 'active' : '' }}"><a href="{{ route('account.credit_logs') }}">Credit Logs</a></li>
    <li class="{{ request()->getUri() === route('account.action_logs') ? 'active' : '' }}"><a href="{{ route('account.action_logs') }}">Action Logs</a></li>
</ul>