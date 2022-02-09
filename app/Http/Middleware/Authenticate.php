<?php

namespace App\Http\Middleware;

use Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function redirectTo($request)
    {
        Log::info('$request->expectsJson()'. json_encode($request->expectsJson()));
        if (! $request->expectsJson()) {
           // return route('login');
           throw new AccessDeniedHttpException("Permission denied.");
        }
    }
}
