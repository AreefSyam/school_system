<?php
// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Support\Facades\Auth;
// use Carbon\Carbon;

// class RememberMeExpiration
// {
//     public function handle($request, Closure $next)
//     {
//         if (Auth::check()) {
//             $user = Auth::user();
//             $lastActivity = session('last_activity_time') ?? Carbon::now();
//             $now = Carbon::now();

//             // Define expiration period for remember me token (e.g., 30 days)
//             $expirationDays = 30;
//             $expirationTime = Carbon::parse($lastActivity)->addDays($expirationDays);

//             if ($now->gt($expirationTime)) {
//                 Auth::logout();
//                 return redirect('/login')->with('error', 'Session expired, please log in again.');
//             }

//             session(['last_activity_time' => $now]);
//         }

//         return $next($request);
//     }
// }

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;

class RememberMeExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && ! session()->has('remember_me')) {
            $user           = Auth::user();
            $lastActivity   = session('last_activity_time') ?? now();
            $expirationTime = Carbon::parse($lastActivity)->addDays(30);

            if (now()->greaterThan($expirationTime)) {
                Auth::logout();
                return redirect('/login')->with('error', 'Session expired, please log in again.');
            }

            session(['last_activity_time' => now()]);
        }

        return $next($request);
    }
}
