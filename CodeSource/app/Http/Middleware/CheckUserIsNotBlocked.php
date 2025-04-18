<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserIsNotBlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_blocked) {
            Auth::logout();
            
            return redirect()->route('login')->with('error', 'Votre compte a été bloqué. Veuillez contacter l\'administrateur pour plus d\'informations.');
        }

        return $next($request);
    }
}
