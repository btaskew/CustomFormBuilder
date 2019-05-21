<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception                $exception
     * @return Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->wantsJson() && !$exception instanceof ValidationException) {
            $errorData = [
                'message' => $exception->getMessage()
            ];

            return response()->json(['error' => $errorData], $exception->getCode());
        }

        return parent::render($request, $exception);
    }
}
