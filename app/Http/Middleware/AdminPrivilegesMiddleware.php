<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPrivilegesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $privileges  Required privileges level (0 = FullAdmin, 1 = Semi-Admin, 2 = Admin)
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $privileges = null)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // If no specific privileges are required, just check if user is an admin
        if ($privileges === null) {
            if (!in_array($user->privileges, [0, 1, 2])) {
                return abort(403, 'Admin access required');
            }
        } else {
            // Check for specific privilege level
            $requiredPrivileges = (int) $privileges;
            
            if ($user->privileges != $requiredPrivileges) {
                return abort(403, 'Insufficient privileges');
            }
        }

        return $next($request);
    }
} 