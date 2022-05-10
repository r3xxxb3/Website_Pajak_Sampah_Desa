<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        // auth()->guard('admin')->logout();
        // dd(Auth::guard($guard)->check());
        if(Auth::guard($guard)->check()){
            return $next($request);
        }else{
            return redirect()->route('admin-login');
        }
    }
}
