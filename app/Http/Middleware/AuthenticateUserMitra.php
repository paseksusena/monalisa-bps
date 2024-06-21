<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateUserMitra
{
    public function handle($request, Closure $next, $guard = 'usermitra')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect('/mitra/login');
        }

        return $next($request);
    }
}
