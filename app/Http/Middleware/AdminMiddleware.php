<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$hasAdver)
    {
        if (Auth::check() && 
            Auth::user()->hasRole('admin') || 
            !empty($hasAdver)) {

            return $next($request);
        }
        return redirect('home');
    }
}
