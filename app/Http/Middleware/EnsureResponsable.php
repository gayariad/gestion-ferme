<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureResponsable
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->role_id != 1) {
            abort(403, 'Accès réservé aux responsables.');
        }
        return $next($request);
    }
}
