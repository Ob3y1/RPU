<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // تحقق من تسجيل الدخول وصلاحية المستخدم كإدمن
        if (Auth::check() && Auth::user()->role_id === 1) { // Assuming 1 is the admin role
            return $next($request);
        }

        return redirect()->route('/')->with('error', 'Access denied. Admins only.');
    }
}

