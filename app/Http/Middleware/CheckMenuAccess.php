<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $menuName): Response
    {
        if (auth()->check() && auth()->user()->canViewMenu($menuName)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke modul ini.');
    }
}
