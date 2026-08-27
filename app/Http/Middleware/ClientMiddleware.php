<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClientMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        /*
         * Only authenticated clients may access
         * client-only routes.
         */
        abort_unless(
            $user && $user->role === 'client',
            403
        );

        return $next($request);
    }
}