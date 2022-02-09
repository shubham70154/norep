<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyEmail extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        Log::info('$request->expectsJson()'. json_encode($request->expectsJson()));
        if (! $request->expectsJson()) {
           // return route('login');
           throw new AccessDeniedHttpException("Permission denied.");
        }
    }
}
