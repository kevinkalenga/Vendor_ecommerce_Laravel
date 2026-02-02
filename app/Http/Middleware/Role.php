<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // table user
        // $user = $request->user();

        if ($request->user()->role !== $role) {
            //if you are not login as an admin it will return to the dashboard 
            return redirect('/dashboard');
        }
        return $next($request);
    }
}
