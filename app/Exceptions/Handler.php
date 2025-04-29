<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    // (Aquí están otras funciones...)

    /**
     * Handle unauthenticated user
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'errors' => [[
                'status' => '401',
                'title' => 'Unauthorized',
                'detail' => 'No estás autenticado o tu token ha expirado.',
            ]]
        ], 401);
    }
}
