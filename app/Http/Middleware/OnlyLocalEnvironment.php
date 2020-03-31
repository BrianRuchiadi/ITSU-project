<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class OnlyLocalEnvironment
{
    public function handle($request, Closure $next)
    {
        if (config('app.env') == 'local') {
            return $next($request);
        }

        abort(401);
    }
}
