<?php
/**
 * @Author: Anwarul
 * @Date: 2025-12-31 11:31:40
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-22 17:05:49
 * @Description: Innova IT
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission = null, $guard = null)
    {
        // ✅ guard resolve
        $guard = $guard ?? Auth::getDefaultDriver();
        $authGuard = Auth::guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        // ✅ permission from param OR route name
        if ($permission) {
            $permissions = is_array($permission)
                ? $permission
                : explode('|', $permission);
        } else {
            $permissions = [$request->route()->getName()];
        }

        // ✅ permission check
        foreach ($permissions as $perm) {
            if ($authGuard->user()->can($perm)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
