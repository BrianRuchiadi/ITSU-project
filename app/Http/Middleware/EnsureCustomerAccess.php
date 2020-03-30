<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\User;

class EnsureCustomerAccess
{
    public function handle($request, Closure $next, $customer)
    {
        if (Auth::user()->acc_customer_module == $customer) {
            return $next($request);
        }

        abort(401);
    }
}
