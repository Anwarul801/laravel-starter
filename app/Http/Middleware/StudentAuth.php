<?php
/**
 * @ Author: Minhazul Abedin(Innova IT)
 * @ Create Time: 2025-05-07 17:01:09
 * @ Modified time: 2026-01-29 10:57:10
 * @ Description: All rights reserved to Innova IT
 */


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('api')->check()) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized Student Token'], 401);
    }
}
