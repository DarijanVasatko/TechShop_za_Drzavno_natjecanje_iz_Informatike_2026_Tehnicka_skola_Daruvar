<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}