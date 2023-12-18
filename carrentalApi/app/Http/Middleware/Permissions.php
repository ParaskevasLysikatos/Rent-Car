<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $role = null;
        if (Auth::id()) {
            $role = Auth::user()->role_id;
        }

        if ($role === null || in_array($role, $roles) || $role == 'admin' || $role == 'root') {
            return $next($request);
        }

        abort(403);
    }
}
