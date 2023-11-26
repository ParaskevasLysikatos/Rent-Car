<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        app()->setLocale($request->segment(1));

        if(Cookie::get('pages') === null){
            Cookie::queue('pages', 20, 60*24*30*365);
        }

        return $next($request);
    }
}
