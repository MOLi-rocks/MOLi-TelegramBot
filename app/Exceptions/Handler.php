<?php

namespace MOLiBot\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Telegram\Bot\Exceptions\TelegramSDKException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TelegramSDKException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return mixed
     */
    public function render($request, Exception $e)
    {
        if (config('app.env') == 'production') {
            if ( $request->is( config('telegram.bot_token') ) ) {
                Log:info($e);
                return response()->json(['massages' => 'Ooops, there is something wrong QQ'], 200);
            } else {
                if ($e instanceof TelegramSDKException) {
                    return response()->json($e->getResponseData(), $e->getHttpStatusCode());
                }

                return response()->json(['massages' => 'Ooops, there is something wrong QQ'], 400);
            }
        } else {
            if ($e instanceof ModelNotFoundException) {
                $e = new NotFoundHttpException($e->getMessage(), $e);
            }

            if ($e instanceof TelegramSDKException) {
                return response()->json($e->getResponseData(), $e->getHttpStatusCode());
            }
        }
        
        return parent::render($request, $e);
    }
}
