<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MultiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        // If no guards specified, check all custom guards
        if (empty($guards)) {
            $guards = ['publicuser', 'agency', 'mcmc'];
        }

        $authenticated = false;
        
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $authenticated = true;
                break;
            }
        }

        if (!$authenticated) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
