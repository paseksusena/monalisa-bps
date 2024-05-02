<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // tambahkan ini

class RedirectIfNotAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->role || $user->role !== 'admin') {
            return redirect('/')->with('error', 'You do not have admin access.');
        }

        return $next($request);
    }
}
