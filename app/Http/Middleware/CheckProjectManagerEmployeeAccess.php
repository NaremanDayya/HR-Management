<?php

namespace App\Http\Middleware;

use App\Models\Employee;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckProjectManagerEmployeeAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'hr_manager', 'hr_assistant'])) {
            return $next($request);
        }

        if ($user->role === 'project_manager') {
            if (!$request->route()->hasParameter('employee')) {
                return $next($request);
            }

            $employee = $request->route('employee');

            if (is_numeric($employee)) {
                $employee = Employee::find($employee);
            }

            if (!$employee) {
                abort(404, 'الموظف غير موجود.');
            }

            $projectManagerId = $employee->project?->manager_id ?? null;

            if ($projectManagerId !== $user->id) {
                abort(403, 'ليس لديك صلاحية الوصول لهذا الموظف.');
            }

            return $next($request);
        }

        abort(403, 'غير مصرح بالدخول.');
    }
}
