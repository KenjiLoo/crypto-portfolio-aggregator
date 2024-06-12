<?php

namespace App\Exceptions;

use App\Exceptions\AuthPermissionDeniedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof AuthPermissionDeniedException) {
                if ($request->expectsJson()) {
                    return api()->unauthenticated()
                        ->message($exception->getMessage())
                        ->flush();
                } else {
                    return response($exception->getMessage(), 400);
                }
            }

            if ($exception instanceof NotFoundHttpException) {
                return api()->notFound()
                    ->message($exception->getMessage() ?: 'Not found.')
                    ->flush();
            }

            if ($exception instanceof ValidationException) {
                $errors = $exception->errors();
                $parsed = [];
                $mesages = [];

                foreach ($errors as $k => $arr) {
                    foreach ($arr as $msg) {
                        if (!in_array($msg, $mesages)) {
                            $mesages[] = $msg;
                            $parsed[$k][] = $msg;
                        }
                    }
                }

                return api()->exception($exception->status)
                    ->message($exception->getMessage())
                    ->errors($parsed)
                    ->flush();
            }

            if (!$exception instanceof NotFoundHttpException && !$exception instanceof AuthenticationException) {
                if (!empty($exception->getMessage())) {
                    log_error('[EXCEPTION] ' . json_encode([
                        'e' => $exception->getMessage(),
                        'url' => request()->url(),
                        'req' => request()->all(),
                        'stack' => substr($exception->getTraceAsString(), 0, 1000),
                    ]), 'error');
                }
            }
        }

        return parent::render($request, $exception);
    }
}
