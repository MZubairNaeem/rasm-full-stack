<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $levels = [
        //
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        //403 Exception JSON Response
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 200);
        }
        
        //404 Exception JSON Response
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }

        //404 Exception JSON Response
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }

        // Unauthenticated Exception JSON Response
        if($exception instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to continue',
                'error_code' => 401
            ], 401);
        }

        //Integrity constraint violation Exception JSON Response
        if ($exception instanceof QueryException && strpos($exception->getMessage(), 'Integrity constraint violation') !== false) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete this item because it contains some resources.',
            ], 403);
        }

        return response()->json([
                'success' => false,
                'message' => 'Unexpected error occured. Error: '. $exception->getMessage(),
            ], 500);
    }
}
