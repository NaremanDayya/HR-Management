<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Models\Role;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return $next($request);
        }

        $role = Role::where('name', $user->role)->first();

        if (($role && $role->hasPermissionTo('manage_privileges')) || $user->hasDirectPermission('manage_privileges')) {
            return $next($request);
        }

        abort(403);
    }
}
