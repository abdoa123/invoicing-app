<?php

namespace App\Exceptions;
use Illuminate\Auth\AuthenticationException;

use Exception;

class ApiException extends Exception
{

protected function render($request, Throwable $exception)
{
    if ($exception instanceof AuthenticationException) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    return parent::render($request, $exception);
}
}
