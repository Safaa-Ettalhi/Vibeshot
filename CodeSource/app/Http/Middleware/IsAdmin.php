<?php
// app/Http/Middleware/IsAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
       
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }
        return redirect()->route('home')->with('error', 'Accès non autorisé.');
    }
}