<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfBlocked
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedRouteNames = ['account.blocked', 'contact', 'contact.store', 'logout'];

        if (
            auth()->check()
            && auth()->user()->isCustomer()
            && auth()->user()->is_blocked
            && ! in_array($request->route()?->getName(), $allowedRouteNames, true)
        ) {
            return redirect()->route('account.blocked');
        }

        return $next($request);
    }
}
