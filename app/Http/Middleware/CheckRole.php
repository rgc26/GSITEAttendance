<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if ($role === 'student' && !$user->isStudent()) {
            return redirect()->route('teacher.dashboard')->with('error', 'Access denied. Students only.');
        }
        
        if ($role === 'teacher' && !$user->isTeacher()) {
            return redirect()->route('student.dashboard')->with('error', 'Access denied. Teachers only.');
        }

        return $next($request);
    }
} 