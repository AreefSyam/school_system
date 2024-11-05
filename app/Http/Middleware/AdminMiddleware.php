<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            // Check if user has the admin role
            if (Auth::user()->role === 'admin') {
                return $next($request);  // Allow access
            } else {
                // Optionally: add a flash message to notify the user why they are redirected
                return redirect('/')->with('error', 'Access denied. Admin role required.');
            }
        }

        // If the user is not authenticated, redirect to login
        return redirect('/login')->with('error', 'Please log in to access this page.');
    }
}
