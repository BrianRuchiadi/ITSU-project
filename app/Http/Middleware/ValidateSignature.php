<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Hashids\Hashids;
use Illuminate\Routing\Middleware\ValidateSignature as Middleware;

class ValidateSignature extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasValidSignature()) {
            if (Route::getFacadeRoot()->current()->uri() == 'reset-password/verify') {
                $hashids = new Hashids(config('app.salt'), 5);
                $hashedId = $hashids->decode($request['id']);
    
                User::where('id', $hashedId)->update(['password_reset' => 0]);
            }
            abort(403);
        }
        else {
            return $next($request);
        }
    }
}
