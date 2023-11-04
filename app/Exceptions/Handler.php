<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Sentry\Laravel\Integration;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;


class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            Integration::captureUnhandledException($e);
        });
    }

    public function render($request, $exception)
    {
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            $message = Response::$statusTexts[$statusCode];

            return apiResponse()
                ->message($message)
                ->send($statusCode);
        }

        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));

            return apiResponse()
                ->message("Does not exist any {$model}")
                ->send(Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof BadRequestException) {
            return apiResponse()
                ->message($exception->getMessage())
                ->send(Response::HTTP_BAD_REQUEST);
        }

        if ($exception instanceof AuthorizationException) {
            return apiResponse()
                ->message($exception->getMessage())
                ->send(Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof AuthenticationException) {
            return apiResponse()
                ->message($exception->getMessage())
                ->send(Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof ValidationException) {
            $errors = $exception->validator->errors()->messages();

            return apiResponse()
                ->errors($errors)
                ->message($exception->getMessage())
                ->send(Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof ClientException) {
            $errors = $exception->getResponse()->getBody();
            $code = $exception->getCode();

            return apiResponse()
                ->errors($errors)
                ->send($code);
        }

        if (env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }

        return apiResponse()
            ->message('Unexpected Error , try later please')
            ->send(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
