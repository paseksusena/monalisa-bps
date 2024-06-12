<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfRegisterEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah pengguna mencoba mengakses halaman /register
        if ($request->is('register')) {
            // Jika ya, alihkan ke halaman login
            return redirect()->route('login');
        }

        return $next($request);
    }
}
