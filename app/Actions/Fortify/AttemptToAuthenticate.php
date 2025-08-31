<?php

namespace App\Actions\Fortify;

use App\Models\EmployeeLoginIp;
use App\Models\User;
use App\Notifications\BlockedIpLoginAttempt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Actions\AttemptToAuthenticate as FortifyAttemptToAuthenticate;

class AttemptToAuthenticate extends FortifyAttemptToAuthenticate
{
    protected function throwFailedAuthenticationException($request)
    {
        $this->limiter->increment($request);

        $user = User::where('email', $request->email)->first();
        $message = trans('auth.failed');

        if (!$user) {
            throw ValidationException::withMessages([
                Fortify::username() => ['البريد الإلكتروني غير مستخدم'],
            ]);
        }

        if ($user->account_status !== 'active') {
            throw ValidationException::withMessages([
                Fortify::username() => ['حسابك غير مفعل، تواصل مع الإدارة لتفعيله'],
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                Fortify::username() => ['كلمة المرور غير صحيحة'],
            ]);
        }

        if ($user->role !== 'admin') {
            $currentIp = $request->ip();
            $employee = $user->employee;

            $mainIp = EmployeeLoginIp::where('employee_id', $employee->id)
                ->where('is_allowed', true)
                ->where('is_temporary', false)
                ->first();

            if (!$mainIp) {
                EmployeeLoginIp::create([
                    'employee_id' => $employee->id,
                    'ip_address' => $currentIp,
                    'is_allowed' => true,
                    'is_temporary' => false,
                ]);
                return;
            }

            if ($mainIp->ip_address === $currentIp) {
                return;
            }

            $temporaryIp = EmployeeLoginIp::where('employee_id', $employee->id)
                ->where('ip_address', $currentIp)
                ->where('is_allowed', true)
                ->where('is_temporary', true)
                ->where(function ($q) {
                    $q->whereNull('allowed_until')
                        ->orWhere('allowed_until', '>=', now());
                })
                ->first();

            if ($temporaryIp) {
                return;
            }

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new BlockedIpLoginAttempt($employee, $currentIp));
            }

            throw ValidationException::withMessages([
                Fortify::username() => ['هذا الجهاز غير مصرح له بتسجيل الدخول إلى النظام'],
            ]);
        }
    }
}
