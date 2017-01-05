<?php

namespace MOLiBot\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \MOLiBot\Http\Middleware\CheckForMaintenanceMode::class,
        \MOLiBot\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Barryvdh\Cors\HandleCors::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \MOLiBot\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \MOLiBot\Http\Middleware\RedirectIfAuthenticated::class,
        'bot.token' => \MOLiBot\Http\Middleware\VerifyAPIToken::class,
    ];
}
