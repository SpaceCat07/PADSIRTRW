<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
    
        // dd(Auth::user());
        // if (Auth::check() || Auth::user() -> role !== $role) {
        //     return redirect('/');
        // }
        // if (Auth::check() && Auth::user() -> role == $role) {
        //     return $next($request);
        // }
        $roles = is_array($role) ? $role : explode('|', $role);

        // dd($roles);
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            return redirect('/');
        }

        return $next($request);
    }
}
