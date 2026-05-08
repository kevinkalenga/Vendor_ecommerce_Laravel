<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::check()) {

            $user = Auth::user();

            if ($request->is('admin/*')) {
                return redirect('/admin/dashboard');
            }

            if ($request->is('vendor/*')) {
                return redirect('/vendor/dashboard');
            }

            return redirect('/dashboard');
        }

        return $next($request);
   }
}