<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            //\App\Http\Middleware\CheckUserIfSuspendedMiddleware::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'access_page.manage_users' => \App\Http\Middleware\ManageUsers\AccessPageMiddleware::class,
        'access_page.vouchers' => \App\Http\Middleware\Vouchers\AccessPageMiddleware::class,
        'access_page.update_jsons' => \App\Http\Middleware\UpdateJsons\AccessPageMiddleware::class,
        'access_page.manage_servers' => \App\Http\Middleware\ManageServers\AccessPageMiddleware::class,
        'access_page.manage_post' => \App\Http\Middleware\NewsAndUpdates\AccessPageMiddleware::class,
        'access_page.support_tickets' => \App\Http\Middleware\SupportTickets\AccessPageMiddleware::class,
        'access_page.settings' => \App\Http\Middleware\Settings\AccessPageMiddleware::class,
        'access_page.pages' => \App\Http\Middleware\Pages\AccessPageMiddleware::class,
        'access_page.account' => \App\Http\Middleware\Account\AccessPageMiddleware::class,
        'access_page.authorized_reseller' => \App\Http\Middleware\AuthorizedResellers\AccessPageMiddleware::class,
        'access_page.seller_monitor' => \App\Http\Middleware\SellerSummary\AccessPageMiddleware::class,
        'access_page.full_credit_logs' => \App\Http\Middleware\Logs\AccessPageMiddleware::class,
    ];
}
