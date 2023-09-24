<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the authenticated user has the 'admin' role
        if (auth()->check() && auth()->user()->hasRole('admin')) {
            return $next($request);
        }

        // If the user doesn't have the 'admin' role, you can redirect them or return a response
        // For example, you can redirect them to the home page with a message.
        return redirect()->route('ee')->with('error', 'Access denied. You must be an admin to access this page.');
      }
}
