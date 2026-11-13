<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Jika role pengguna yang sedang login tidak sama dengan role yang diminta URL
        if ($request->user()->role !== $role) {
            // Tendang balik ke jalurnya masing-masing
            if ($request->user()->role === 'kasir') {
                return redirect()->route('kasir.pos');
            } elseif ($request->user()->role === 'owner') {
                return redirect()->route('owner.dashboard');
            }
        }

        return $next($request);
    }
}