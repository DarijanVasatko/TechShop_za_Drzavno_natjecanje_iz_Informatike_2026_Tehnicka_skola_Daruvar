<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Nemate ovlasti za pristup ovoj stranici.');
    }
}