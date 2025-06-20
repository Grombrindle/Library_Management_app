<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TeacherAuthMiddleware
{
    /**
     * Handle an incoming request and ensure it's from an authenticated teacher.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and is a teacher (privileges=0)
        if (!Auth::check() || Auth::user()->privileges != 0 || !Session::has('teacher')) {
            return abort(403, 'Teacher access required');
        }
        
        // Ensure the teacher ID in session belongs to the authenticated user
        $teacherId = Session::get('teacher');
        
        // Check if the teacher making the request is the same as the authenticated teacher
        if ($request->has('teacher_id') && $request->teacher_id != $teacherId) {
            return abort(403, 'Unauthorized');
        }
        
        return $next($request);
    }
} 