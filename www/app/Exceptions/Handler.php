<?php
namespace App\Exceptions;
use illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler{
    public function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['message' => $exception->getMessage()], 401);
    }
}