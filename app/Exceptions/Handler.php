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
        $res = new Response();

        if (config('app.env') === 'production') {
            if ( $request->is( config('moli.telegram.bot_token') ) ) {
                Log:info($e);
                return $res->jsonResponse(200, -1);
            }

            if ($e instanceof TelegramResponseException) {
                return $res->jsonResponse(
                    $e->getHttpStatusCode(),
                    -1,
                    $e->getErrorType(),
                    $e->getResponseData()
                );
            }

            if ($e instanceof DataSourceRetriveException) {
                return $res->jsonResponse(404, -1, 'Data Retrive Failed');
            }

            if ($e instanceof ModelNotFoundException) {
                return $res->jsonResponse(404, -1, 'Not Found');
            }

            return $res->jsonResponse(400, -1, 'Failed');
        }

        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }
        
        return parent::render($request, $e);
    }
}
