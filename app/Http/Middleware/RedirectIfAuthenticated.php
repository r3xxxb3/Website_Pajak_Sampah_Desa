<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // dd('test1');
        if (Auth::guard($guard)->check()) {
            // dd('test2');
            return redirect(RouteServiceProvider::HOME);
        }elseif(Auth::guard('admin')->check()){
            return redirect()->route('admin-dashboard');
        }
        // dd('test3');
        return $next($request);
    }
}
