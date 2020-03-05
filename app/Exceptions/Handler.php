<?php

namespace MOLiBot\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MOLiBot\Http\Responses\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Telegram\Bot\Exceptions\TelegramResponseException;
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
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        $res = new Response();

        if (config('app.env') === 'production') {
            if ( $request->is( config('moli.telegram.bot_token') ) ) {
                Log:info($exception);
                return $res->jsonResponse(200, -1);
            }

            if ($exception instanceof TelegramResponseException) {
                return $res->jsonResponse(
                    $exception->getHttpStatusCode(),
                    -1,
                    $exception->getErrorType(),
                    $exception->getResponseData()
                );
            }

            if ($exception instanceof DataSourceRetriveException) {
                return $res->jsonResponse(404, -1, 'Data Retrive Failed');
            }

            if ($exception instanceof ModelNotFoundException) {
                return $res->jsonResponse(404, -1, 'Not Found');
            }

            return $res->jsonResponse(400, -1, 'Failed');
        }

        if ($exception instanceof ModelNotFoundException) {
            $exception = new NotFoundHttpException($exception->getMessage(), $exception);
        }
        
        return parent::render($request, $exception);
    }
}
