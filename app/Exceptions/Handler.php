<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Throwable;
use Exception;

class Handler extends ExceptionHandler
{
    use ApiResponse;
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
        });
    }

    public function render($request, $exception)
    {
        if ($exception instanceof BadRequestException){
            $error = $exception->getMessage();

            return $this->errorResponse(message: $error , status: Response::HTTP_BAD_REQUEST);
        }

        if ($exception instanceof ValidationException){

            $errors = $exception->validator->errors();

            return $this->errorResponse(errors: $errors->toArray() , status: Response::HTTP_UNPROCESSABLE_ENTITY);
        }

       return parent::render($request, $exception);
    }
}
