<?php

namespace MOLiBot\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Telegram;
use Log;

class CheckForMaintenanceMode
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->app->isDownForMaintenance()) {
            if ($request->is( env('TELEGRAM_BOT_TOKEN') )) {
                $msgfrom = $request->all()['message']['chat']['id'];
                Telegram::sendMessage([
                    'chat_id' => $msgfrom,
                    'text' => 'Bot is under Maintenance'
                ]);
                abort(200);
            }

            return response()->json([
                'ok' => false,
                'error_code' => 503,
                'description' => '[Error]: Bot is under Maintenance'
            ], 503);
        }

        return $next($request);
    }
}
