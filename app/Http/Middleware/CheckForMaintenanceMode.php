<?php

namespace MOLiBot\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpKernel\Exception\HttpException;

use MOLiBot\Services\TelegramService;

class CheckForMaintenanceMode
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * @var telegramService
     */
    private $telegramService;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param TelegramService $telegramService
     * @return void
     */
    public function __construct(Application $app,
                                TelegramService $telegramService)
    {
        $this->app = $app;
        $this->telegramService = $telegramService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @throws
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->is( config('ncdr.url') )) { // 讓 NCDR 資料不受 Maintenance Mode 影響
            return $next($request);
        }

        if ($this->app->isDownForMaintenance()) {
            if ($request->is( config('moli.telegram.bot_token') )) {
                $msgfrom = $request->all()['message']['chat']['id'];
                $this->telegramService->sendMessage(
                    $msgfrom,
                    'Bot is under Maintenance'
                );
                return response('OK', 200);
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
