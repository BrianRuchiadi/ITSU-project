<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\User;

class EnsureContractAccess
{
    public function handle($request, Closure $next, $contract)
    {
        if (Auth::user()->acc_contract_module == $contract) {
            return $next($request);
        }

        abort(401);
    }
}
