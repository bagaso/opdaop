
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ app('settings')->site_name }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/theme/default/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/theme/default/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/theme/default/bower_components/Ionicons/css/ionicons.min.css">


@stack('styles')

<!-- Theme style -->
    <link rel="stylesheet" href="/theme/default/dist/css/AdminLTE.min.css">

<!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/theme/default/dist/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition fixed skin-blue sidebar-mini">
<div class="wrapper">

    <!-- class="main-header" -->
    <header class="main-header">
        <!-- Logo -->
        <a href="../../index2.html" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>VPN</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>{{ app('settings')->site_name }}</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            @auth
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Tasks: style can be found in dropdown.less -->
                        <li class="dropdown tasks-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning">10</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">User Summary</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> {{ \App\User::TotalUsers()->count() }} total users
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> {{ \App\User::NewUsersThisWeek()->count() }} users joined this week
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> {{ \App\User::NewUsersThisMonth()->count() }} users joined this month
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> {{ \App\User::ActiveUsers()->count() }} active users
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> {{ \App\User::InActiveUsers()->count() }} inactive users
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> {{ \App\User::SuspendedUsers()->count() }} suspended users
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> {{ \App\User::ActivePaidUsers()->count() }} paid users
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> {{ \App\User::UsersExpiresThisWeek()->count() }} users expires this week
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> {{ \App\User::UsersExpiresThisMonth()->count() }} users expires this month
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> {{ \App\User::BronzeUsers()->count() }} bronze users
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> {{ \App\User::SilverUsers()->count() }} silver users
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> {{ \App\User::GoldUsers()->count() }} gold users
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> {{ \App\User::DiamondUsers()->count() }} diamond users
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ auth()->user()->photo ? 'data:image/jpeg;base64,' . auth()->user()->photo : '/img/default.png' }}" class="user-image" alt="User Image">
                                <span class="hidden-xs">{{ auth()->user()->username }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="{{ auth()->user()->photo ? 'data:image/jpeg;base64,' . auth()->user()->photo : '/img/default.png' }}" class="img-circle" alt="User Image">

                                    <p>
                                        {{ auth()->user()->fullname }} - <span class="label label-{{ auth()->user()->group->class }}">{{ auth()->user()->group->name }}</span>
                                        <small>Member since {{ auth()->user()->created_at }}</small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" href="{{ route('account.profile') }}" class="btn btn-default btn-flat">My Account</a>
                                    </div>
                                    <div class="pull-right">
                                        <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Sign out
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            @endauth

            @guest

            @endguest
        </nav>
    </header>
    <!-- end class="main-header" -->

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                @auth
                    <div class="pull-left image">
                        <img src="{{ auth()->user()->photo ? 'data:image/jpeg;base64,' . auth()->user()->photo : '/img/default.png' }}" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>{{ auth()->user()->username }}</p>
                        <p>
                            <span class="label label-{{ auth()->user()->group->class }}">{{ auth()->user()->group->name }}</span>
                            @can('MANAGE_USER')
                                <i class="fa fa-fw fa-check-circle" style="color: #00FF00; text-align: center;"></i>
                            @endcan
                            @cannot('MANAGE_USER')
                                <i class="fa fa-fw fa-times-circle" style="color: #FF0000; text-align: center;"></i>
                            @endcannot
                        </p>
                    </div>
                @endauth
                @guest
                    <div class="pull-left image">
                        <img src="/img/guest.png" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>Welcome Guest!</p>
                        <p><a href="{{ route('login') }}"><small>Please Login</small></a></p>
                    </div>
                @endguest
            </div>

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li><a href="{{ route('news_and_updates') }}"><i class="fa fa-book"></i> <span>News and Updates</span></a></li>
                @guest
                @if(app('settings')->enable_authorized_reseller && app('settings')->public_authorized_reseller)
                <li><a href="{{ route('authorized_reseller') }}"><i class="fa fa-book"></i> <span>Authorized Resellers</span></a></li>
                @endif
                @if(app('settings')->enable_online_users && app('settings')->public_online_users)
                <li><a href="{{ route('online_users') }}"><i class="fa fa-book"></i> <span>Online Users</span></a></li>
                @endif
                <li><a href="{{ route('login') }}"><i class="fa fa-book"></i> <span>Login</span></a></li>
                @endguest
                @auth
                <li><a href="{{ route('account.profile') }}"><i class="fa fa-book"></i> <span>My Account</span></a></li>
                @can('MANAGE_USER')
                <li><a href="{{ route('manage_users.view_all') }}"><i class="fa fa-book"></i> <span>Manager Users</span></a></li>
                @endcan
                @can('ACCESS_SELLER_MONITOR')
                <li><a href="{{ route('seller_summary.renew') }}"><i class="fa fa-book"></i> <span>Seller Monitoring</span></a></li>
                @endcan
                @can('MANAGE_VOUCHER')
                <li><a href="{{ route('vouchers') }}"><i class="fa fa-book"></i> <span>Vouchers</span></a></li>
                @endcan
                @if(auth()->user()->isAdmin() || app('settings')->enable_authorized_reseller)
                <li><a href="{{ route('authorized_reseller') }}"><i class="fa fa-book"></i> <span>Authorized Resellers</span></a></li>
                @endif
                @if(auth()->user()->isAdmin() || app('settings')->enable_online_users)
                <li><a href="{{ route('online_users') }}"><i class="fa fa-book"></i> <span>Online Users</span></a></li>
                @endif
                @can('MANAGE_SITE_SETTINGS')
                <li><a href="{{ route('settings') }}"><i class="fa fa-book"></i> <span>Settings</span></a></li>
                @endcan
                @can('MANAGE_PAGES')
                <li><a href="{{ route('pages') }}"><i class="fa fa-book"></i> <span>Pages</span></a></li>
                @endcan
                @can('MANAGE_SERVER')
                <li><a href="{{ route('manage_servers') }}"><i class="fa fa-book"></i> <span>Servers</span></a></li>
                @endcan
                @can('MANAGE_UPDATE_JSON')
                <li><a href="{{ route('json') }}"><i class="fa fa-book"></i> <span>Json File Update</span></a></li>
                @endcan
                @can('ACCESS_FULL_CREDIT_LOGS')
                <li><a href="{{ route('logs') }}"><i class="fa fa-book"></i> <span>Logs</span></a></li>
                @endcan
                <li><a href="{{ route('support_tickets') }}"><i class="fa fa-book"></i> <span>Support Ticket</span></a></li>
                @endauth
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>


    @yield('panel_content')


    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>VPN Panel</b> v4.0
        </div>
        <strong>Copyright &copy; {{ \Carbon\Carbon::now()->year }}.</strong> All rights reserved.
    </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="/theme/default/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/theme/default/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="/theme/default/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/theme/default/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/theme/default/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/theme/default/dist/js/demo.js"></script>

@stack('scripts')

</body>
</html>
