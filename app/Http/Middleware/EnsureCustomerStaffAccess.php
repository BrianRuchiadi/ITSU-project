<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\User;

class EnsureCustomerStaffAccess
{
    public function handle($request, Closure $next, $customer)
    {
        if (Auth::user()->branchind == $customer) {
            return $next($request);
        }

        abort(401);
    }
}
