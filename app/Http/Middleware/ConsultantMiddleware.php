<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConsultantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        /*
         * Only authenticated consultants may access
         * consultant-only routes.
         */
        abort_unless(
            $user && $user->isConsultant(),
            403
        );

        return $next($request);
    }
}