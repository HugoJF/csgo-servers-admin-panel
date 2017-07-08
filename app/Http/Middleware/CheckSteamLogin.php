<?php

namespace App\Http\Middleware;

use Closure;
use Invisnik\LaravelSteamAuth\SteamAuth;
use Auth;

class CheckSteamLogin
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
        if(!Auth::check()) {
            return redirect('/login');
        }

        return $next($request);
    }
}
