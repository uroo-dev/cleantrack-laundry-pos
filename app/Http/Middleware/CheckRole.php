<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if ($userRole === 'super_admin') {
            return $next($request);
        }

        foreach ($roles as $role) {
            if ($userRole === $role) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access.');
    }
}
