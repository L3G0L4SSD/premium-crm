<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->role || $user->role->slug !== 'super-admin') {
            abort(403, 'Bu sayfaya erişim yetkiniz bulunmamaktadır.');
        }

        return $next($request);
    }
}
